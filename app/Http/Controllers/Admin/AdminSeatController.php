<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;
use App\Enums\SeatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\RoomSeatConfiguration;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminSeatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view role')->only('index');
        $this->middleware('can:create role')->only(['create', 'store']);
        $this->middleware('can:edit role')->only(['edit', 'update']);
        $this->middleware('can:delete role')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = $request->input('query');
        $seatTypeId = $request->input('seat_type_id');

        $seatTypes = SeatType::all();

        $seats = Seat::with('room', 'seatType')
            ->when($query, function ($queryBuilder, $query) {
                return $queryBuilder->where('row_char', 'like', "%{$query}%")
                    ->orWhere('seat_number', 'like', "%{$query}%");
            })
            ->when($seatTypeId, function ($queryBuilder, $seatTypeId) {
                return $queryBuilder->where('seat_type_id', $seatTypeId);
            })
            ->paginate(10);

        return view('admin.seats.index', compact('seats', 'seatTypes'));
    }

    public function create(Room $room)
    {
        $roomSeatValidation = new \App\Services\RoomSeatValidationService();
        $layoutOptimizer = new \App\Services\SeatLayoutOptimizer();
        
        $allowedSeatTypes = $room->allowedSeatTypes();
        $recommendedConfig = $roomSeatValidation->getRecommendedSeatConfiguration($room);

        // Get optimized layout suggestions
        $layoutSuggestions = $layoutOptimizer->getLayoutSuggestions($room);

        $roomSeatConfigs = RoomSeatConfiguration::where('room_id', $room->id)
            ->with('seatType')
            ->get()
            ->pluck('percentage', 'seat_type_id')
            ->toArray();

        $seatPercentages = [];
        if ($roomSeatConfigs) {
            $seatPercentages = $roomSeatConfigs;
        } else {
            foreach ($recommendedConfig as $seatTypeId => $config) {
                $seatPercentages[$seatTypeId] = $config['suggested_percentage'];
            }
        }

        $seats = Seat::where('room_id', $room->id)->with('seatType')->get();
        $maxRows = 26;
        $maxSeatsPerRow = 50;
        $rows = range('A', chr(64 + $maxRows));

        return view('admin.seats.create', compact(
            'room', 
            'allowedSeatTypes', 
            'recommendedConfig',
            'seatPercentages', 
            'seats', 
            'maxRows', 
            'maxSeatsPerRow', 
            'rows',
            'layoutSuggestions'
        ));
    }

    public function store(\App\Http\Requests\CreateSeatsRequest $request)
    {
        \Log::info('---[START STORE SEAT]---');
        \Log::info('Request data: ', $request->all());

        try {
            $roomSeatValidation = new \App\Services\RoomSeatValidationService();
            $seatManagementService = new \App\Services\SeatManagementService($roomSeatValidation);
            
            $room = Room::findOrFail($request->room_id);
            
            // Validate room status
            if ($room->status !== 'active') {
                return redirect()->back()
                    ->withErrors(['room_id' => 'Phòng chiếu không ở trạng thái hoạt động.'])
                    ->withInput();
            }

            // Create seats using the management service
            $ignoreConstraints = $request->boolean('ignore_constraints', false);
            $result = $seatManagementService->createSeatsForRoom($request->validated(), $ignoreConstraints);
            
            if (!$result['success']) {
                return redirect()->back()
                    ->withErrors($result['errors'])
                    ->withInput();
            }

            \Log::info('Seats created successfully', $result['data']);
            
            return redirect()
                ->route('admin.rooms.show', $room->id)
                ->with('success', 'Tạo ghế thành công! ' . $result['message']);
                
        } catch (\Exception $e) {
            \Log::error('Error creating seats: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi tạo ghế: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $seat = Seat::with('room', 'seatType')->findOrFail($id);
        $rooms = Room::all();
        $seatTypes = SeatType::all();
        session(['return_room_id' => $seat->room_id]);
        return view('admin.seats.edit', compact('seat', 'rooms', 'seatTypes'));
    }

    public function update(Request $request, $id)
    {
        $seat = Seat::with('seatType')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:' . implode(',', array_column(SeatStatus::cases(), 'value')),
        ]);

        \Log::info('Updating seat ' . $id . ' with status: ' . $request->input('status'));
        
        try {
            DB::beginTransaction();

            // Cập nhật ghế chính
            $seat->update([
                'status' => $request->input('status'),
            ]);

            // Nếu là ghế đôi, cập nhât ghế partner
            if ($seat->seatType && $this->isCoupleSeaType($seat->seatType->name)) {
                $couplePartner = $this->findCouplePartner($seat);
                
                if ($couplePartner) {
                    $couplePartner->update([
                        'status' => $request->input('status'),
                    ]);
                    
                    \Log::info('Also updated couple partner seat ' . $couplePartner->id . ' with same status');
                }
            }

            DB::commit();

            $roomId = session('return_room_id', $seat->room_id);
            session()->forget('return_room_id');
            
            $message = $seat->seatType && $this->isCoupleSeaType($seat->seatType->name) 
                ? 'Cập nhật ghế đôi thành công!'
                : 'Cập nhật ghế thành công!';

            return redirect()->route('admin.rooms.show', ['room' => $roomId])->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating seat: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật ghế: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $seat = Seat::findOrFail($id);
        $roomId = $seat->room_id;
        $seat->delete();

        return redirect()->route('admin.rooms.show', ['room' => $roomId])->with('success', 'Xóa ghế thành công!');
    }

    public function bulkDestroy(Request $request, Room $room)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        try {
            DB::beginTransaction();
            
            $deletedCount = Seat::whereIn('id', $request->seat_ids)
                ->where('room_id', $room->id)
                ->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.rooms.show', $room->id)
                ->with('success', "Đã xóa thành công {$deletedCount} ghế!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi xóa ghế: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Hiển thị form chỉnh sửa hàng loạt ghế
     */
    public function editBulk(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        // Lấy danh sách ghế đã chọn
        $selectedSeats = Seat::with(['seatType', 'room'])
            ->whereIn('id', $request->seat_ids)
            ->get();

        if ($selectedSeats->isEmpty()) {
            return redirect()->back()
                ->withErrors(['error' => 'Không tìm thấy ghế nào để chỉnh sửa.']);
        }

        // Thêm ghế đôi tự động nếu cần
        $finalSeats = $this->addCoupleSeatsIfNeeded($selectedSeats);
        
        $room = $selectedSeats->first()->room;
        $seatStatuses = SeatStatus::cases();

        return view('admin.seats.edit-bulk', compact('finalSeats', 'room', 'seatStatuses'));
    }

    /**
     * Cập nhật hàng loạt ghế
     */
    public function updateBulk(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
            'status' => 'required|string|in:' . implode(',', array_column(SeatStatus::cases(), 'value')),
        ]);

        try {
            DB::beginTransaction();

            $selectedSeats = Seat::with('seatType')
                ->whereIn('id', $request->seat_ids)
                ->get();

            // Thêm ghế đôi tự động nếu cần
            $finalSeats = $this->addCoupleSeatsIfNeeded($selectedSeats);
            $finalSeatIds = $finalSeats->pluck('id')->toArray();

            $updateData = [
                'status' => $request->status,
            ];

            $updatedCount = Seat::whereIn('id', $finalSeatIds)->update($updateData);

            DB::commit();

            $roomId = $selectedSeats->first()->room_id;
            $coupleMessage = count($finalSeatIds) > count($request->seat_ids) 
                ? " (bao gồm cả ghế đôi liên quan)" 
                : "";

            return redirect()
                ->route('admin.rooms.show', $roomId)
                ->with('success', "Đã cập nhật thành công {$updatedCount} ghế{$coupleMessage}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật ghế: ' . $e->getMessage()]);
        }
    }

    /**
     * Thêm ghế đôi tự động nếu cần thiết
     */
    private function addCoupleSeatsIfNeeded($selectedSeats)
    {
        $finalSeats = collect($selectedSeats);
        $addedSeatIds = [];

        foreach ($selectedSeats as $seat) {
            // Kiểm tra xem có phải là ghế đôi không
            if ($seat->seatType && $this->isCoupleSeaType($seat->seatType->name)) {
                $couplePartner = $this->findCouplePartner($seat);
                
                if ($couplePartner && !$finalSeats->contains('id', $couplePartner->id) && !in_array($couplePartner->id, $addedSeatIds)) {
                    $finalSeats->push($couplePartner);
                    $addedSeatIds[] = $couplePartner->id;
                }
            }
        }

        return $finalSeats;
    }

    /**
     * Tìm ghế đôi liên quan
     */
    private function findCouplePartner(Seat $seat)
    {
        $rowChar = $seat->row_char;
        $seatNumber = $seat->seat_number;
        $roomId = $seat->room_id;

        // Logic ghế đôi: A1-A2, A3-A4, B1-B2, etc.
        if ($seatNumber % 2 === 1) {
            // Ghế lẻ, tìm ghế chẵn kế tiếp
            $partnerSeatNumber = $seatNumber + 1;
        } else {
            // Ghế chẵn, tìm ghế lẻ trước đó
            $partnerSeatNumber = $seatNumber - 1;
        }

        return Seat::where('room_id', $roomId)
            ->where('row_char', $rowChar)
            ->where('seat_number', $partnerSeatNumber)
            ->first();
    }

    /**
     * Kiểm tra xem loại ghế có phải là ghế đôi không
     */
    private function isCoupleSeaType(string $seatTypeName): bool
    {
        $typeName = strtolower($seatTypeName);
        return str_contains($typeName, 'couple') || 
               str_contains($typeName, 'sweetbox') || 
               str_contains($typeName, 'bed') ||
               str_contains($typeName, 'sofa');
    }

    /**
     * Import seats from CSV file
     */
    public function importExcel(Request $request)
    {
        \Log::info('---[IMPORT SEAT CSV START]---');
        $request->validate([
            'excel_file' => 'required|file|mimes:csv,txt',
        ]);
        if (!$request->hasFile('excel_file')) {
            \Log::error('No file uploaded!');
            return back()->withErrors(['error' => 'Không nhận được file upload!']);
        }
        $file = $request->file('excel_file');
        \Log::info('File info', ['original_name' => $file->getClientOriginalName(), 'mime' => $file->getMimeType()]);
        $path = $file->getRealPath();
        \Log::info('CSV file path: ' . $path);
        
        try {
            // Read CSV file manually
            $rows = [];
            if (($handle = fopen($path, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $rows[] = $data;
                }
                fclose($handle);
            }
            \Log::info('CSV rows loaded', ['rows_count' => count($rows)]);
        } catch (\Throwable $e) {
            \Log::error('Error reading CSV: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Không đọc được file CSV: ' . $e->getMessage()]);
        }

        $errors = [];
        $imported = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                \Log::info('Header row: ', $row);
                continue; // Bỏ qua header
            }

            // Get room_id from request
            $roomId = $request->input('room_id');
            
            // Find seat type by name
            $seatTypeName = $row[2] ?? null;
            $seatType = \App\Models\SeatType::where('name', $seatTypeName)->first();
            
            $data = [
                'room_id' => $roomId,
                'row_char' => $row[0] ?? null,
                'seat_number' => str_pad($row[1] ?? null, 2, '0', STR_PAD_LEFT),
                'seat_type_id' => $seatType ? $seatType->id : null,
                'status' => $row[3] ?? 'available',
            ];
            \Log::info('Processing row', ['index' => $index, 'data' => $data, 'seat_type_name' => $seatTypeName]);

            $validator = \Validator::make($data, [
                'room_id' => 'required|exists:rooms,id',
                'row_char' => 'required|string|max:2',
                'seat_number' => 'required|string|max:3',
                'seat_type_id' => 'required|exists:seat_types,id',
                'status' => 'required|in:available,unavailable,maintenance',
            ]);

            if ($validator->fails()) {
                $errorMsg = "Dòng " . ($index + 1) . ": " . implode(', ', $validator->errors()->all());
                $errors[] = $errorMsg;
                \Log::warning('Validation failed', ['index' => $index, 'errors' => $validator->errors()->all()]);
                continue;
            }

            $seat = \App\Models\Seat::updateOrCreate(
                [
                    'room_id' => $data['room_id'],
                    'row_char' => $data['row_char'],
                    'seat_number' => $data['seat_number'],
                ],
                [
                    'seat_type_id' => $data['seat_type_id'],
                    'status' => $data['status'],
                ]
            );
            $imported++;
            \Log::info('Seat imported/updated', ['seat_id' => $seat->id]);
        }

        \Log::info('---[IMPORT SEAT EXCEL END]---', ['imported' => $imported, 'errors' => $errors]);

        if ($errors) {
            return back()->withErrors($errors);
        }

        return back()->with('success', "Đã import thành công {$imported} ghế!");
    }
}
