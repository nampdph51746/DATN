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
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function show($id)
    {
        $seat = Seat::with('room', 'seatType')->findOrFail($id);
        return view('admin.seats.show', compact('seat'));
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
     * Xóa ghế hàng loạt
     */
    public function deleteBulk(Request $request)
    {
        \Log::info('=== BULK DELETE CONTROLLER DEBUG ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request URL: ' . $request->fullUrl());
        \Log::info('Request data: ', $request->all());
        \Log::info('Seat IDs: ', $request->seat_ids ?? []);
        \Log::info('Room ID: ' . ($request->room_id ?? 'null'));

        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        \Log::info('Validation passed');

        try {
            DB::beginTransaction();

            $room = Room::findOrFail($request->room_id);

            // Kiểm tra xem phòng có suất chiếu đang hoạt động không
            $hasActiveShowtimes = \App\Models\Showtime::where('room_id', $room->id)
                ->whereIn('status', ['scheduled', 'ongoing'])
                ->exists();

            if ($hasActiveShowtimes) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['error' => 'Không thể xóa ghế khi phòng có suất chiếu đang hoạt động (scheduled hoặc ongoing). Vui lòng hủy hoặc hoàn thành tất cả suất chiếu trước khi xóa ghế.']);
            }

            $selectedSeats = Seat::with(['seatType', 'tickets', 'showtimeSeatStates'])
                ->whereIn('id', $request->seat_ids)
                ->get();

            // Kiểm tra xem có ghế nào đã từng có ai đặt không
            $seatsWithBookings = [];
            $deletableSeats = [];

            foreach ($selectedSeats as $seat) {
                $hasBookings = $this->checkSeatHasBookings($seat);
                
                if ($hasBookings) {
                    $seatsWithBookings[] = $seat->row_char . str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT);
                } else {
                    $deletableSeats[] = $seat;
                }
            }

            // Nếu có ghế đã từng được đặt, không cho phép xóa
            if (!empty($seatsWithBookings)) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['error' => 'Không thể xóa các ghế sau vì đã từng có người đặt: ' . implode(', ', $seatsWithBookings)]);
            }

            // Thêm ghế đôi tự động nếu cần
            $finalSeats = $this->addCoupleSeatsForDeletion($deletableSeats);
            
            // Kiểm tra lại ghế đôi có booking không
            $coupleSeatsWithBookings = [];
            $finalDeletableSeats = [];

            foreach ($finalSeats as $seat) {
                $hasBookings = $this->checkSeatHasBookings($seat);
                
                if ($hasBookings) {
                    $coupleSeatsWithBookings[] = $seat->row_char . str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT);
                } else {
                    $finalDeletableSeats[] = $seat;
                }
            }

            if (!empty($coupleSeatsWithBookings)) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['error' => 'Không thể xóa vì ghế đôi liên quan đã từng có người đặt: ' . implode(', ', $coupleSeatsWithBookings)]);
            }

            $finalSeatIds = collect($finalDeletableSeats)->pluck('id')->toArray();

            // Xóa các bản ghi liên quan trước
            \App\Models\ShowtimeSeatState::whereIn('seat_id', $finalSeatIds)->delete();
            
            // Xóa ghế
            $deletedCount = Seat::whereIn('id', $finalSeatIds)->delete();

            DB::commit();

            \Log::info('Bulk delete completed successfully');
            \Log::info('Deleted ' . $deletedCount . ' seats');
            \Log::info('Final seat IDs: ', $finalSeatIds);

            $coupleMessage = count($finalSeatIds) > count($request->seat_ids) 
                ? " (bao gồm cả ghế đôi liên quan)" 
                : "";

            \Log::info('Redirecting to room show page with room_id: ' . $request->room_id);

            return redirect()
                ->route('admin.rooms.show', $request->room_id)
                ->with('success', "Đã xóa thành công {$deletedCount} ghế{$coupleMessage}!");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting seats: ' . $e->getMessage());
            \Log::error('Exception details: ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi xóa ghế: ' . $e->getMessage()]);
        }
    }

    /**
     * Kiểm tra ghế có từng được đặt không
     */
    private function checkSeatHasBookings(Seat $seat): bool
    {
        // Kiểm tra trong bảng tickets (đã thanh toán thành công)
        $hasTickets = $seat->tickets()->exists();
        
        // Kiểm tra trong bảng showtime_seat_states (đã từng booked)
        $hasBookedStates = $seat->showtimeSeatStates()
            ->where('status', \App\Enums\SeatStatus::Booked)
            ->exists();
            
        return $hasTickets || $hasBookedStates;
    }

    /**
     * Thêm ghế đôi tự động cho việc xóa
     */
    private function addCoupleSeatsForDeletion($selectedSeats)
    {
        $finalSeats = collect($selectedSeats);
        $addedSeatIds = [];

        foreach ($selectedSeats as $seat) {
            // Kiểm tra xem có phải là ghế đôi không
            if ($seat->seatType && $this->isCoupleSeaType($seat->seatType->name)) {
                $couplePartner = $this->findCouplePartner($seat);
                
                if ($couplePartner && !$finalSeats->contains('id', $couplePartner->id) && !in_array($couplePartner->id, $addedSeatIds)) {
                    // Load relationship cho ghế đôi
                    $couplePartner->load(['seatType', 'tickets', 'showtimeSeatStates']);
                    $finalSeats->push($couplePartner);
                    $addedSeatIds[] = $couplePartner->id;
                }
            }
        }

        return $finalSeats;
    }

    /**
     * Import seats from Excel/CSV file
     */
    public function importExcel(Request $request)
    {
        \Log::info('---[IMPORT SEAT EXCEL/CSV START]---');
        $request->validate([
            'excel_file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);
        if (!$request->hasFile('excel_file')) {
            \Log::error('No file uploaded!');
            return back()->with('import_error', 'Không nhận được file upload!');
        }
        $file = $request->file('excel_file');
        \Log::info('File info', ['original_name' => $file->getClientOriginalName(), 'mime' => $file->getMimeType()]);
        $path = $file->getRealPath();
        \Log::info('Excel/CSV file path: ' . $path);
        
        try {
            $rows = [];
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (in_array($extension, ['csv', 'txt'])) {
                // Xử lý file CSV
                if (($handle = fopen($path, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
                \Log::info('CSV rows loaded', ['rows_count' => count($rows)]);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                // Xử lý file Excel
                $spreadsheet = IOFactory::load($path);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                
                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = [];
                    for ($col = 'A'; $col <= $highestColumn; $col++) {
                        $cellValue = $worksheet->getCell($col . $row)->getValue();
                        $rowData[] = $cellValue;
                    }
                    $rows[] = $rowData;
                }
                \Log::info('Excel rows loaded', ['rows_count' => count($rows)]);
            } else {
                throw new \Exception('Định dạng file không được hỗ trợ');
            }
        } catch (\Throwable $e) {
            \Log::error('Error reading file: ' . $e->getMessage());
            return back()->with('import_error', 'Không đọc được file: ' . $e->getMessage());
        }

        $errors = [];
        $imported = 0;
        $skipped = 0; // Đếm số ghế bị bỏ qua

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

            // Kiểm tra xem vị trí này đã có ghế chưa (normalize seat_number để handle cả format "1" và "01")
            $seatNumber = $data['seat_number'];
            $seatNumberAlt = ltrim($seatNumber, '0') ?: '0'; // "01" -> "1", "10" -> "10"
            
            \Log::info('Checking seat position', [
                'room_id' => $data['room_id'],
                'row_char' => $data['row_char'],
                'seat_number_original' => $seatNumber,
                'seat_number_alternative' => $seatNumberAlt
            ]);
            
            $existingSeat = \App\Models\Seat::where('room_id', $data['room_id'])
                ->where('row_char', $data['row_char'])
                ->where(function($query) use ($seatNumber, $seatNumberAlt) {
                    $query->where('seat_number', $seatNumber)
                          ->orWhere('seat_number', $seatNumberAlt);
                })
                ->first();

            if ($existingSeat) {
                // Nếu vị trí đã có ghế, bỏ qua và log thông tin
                $skipped++;
                \Log::info('Seat position already exists, skipping', [
                    'room_id' => $data['room_id'],
                    'row_char' => $data['row_char'],
                    'seat_number_searched' => $seatNumber,
                    'seat_number_alt' => $seatNumberAlt,
                    'existing_seat_id' => $existingSeat->id,
                    'existing_seat_number' => $existingSeat->seat_number
                ]);
                continue;
            }

            // Chỉ tạo ghế mới nếu vị trí trống
            $seat = \App\Models\Seat::create([
                'room_id' => $data['room_id'],
                'row_char' => $data['row_char'],
                'seat_number' => $data['seat_number'],
                'seat_type_id' => $data['seat_type_id'],
                'status' => $data['status'],
            ]);
            $imported++;
            \Log::info('New seat created', ['seat_id' => $seat->id]);
        }

        \Log::info('---[IMPORT SEAT EXCEL END]---', ['imported' => $imported, 'skipped' => $skipped, 'errors' => $errors]);

        if ($errors) {
            return back()->with('import_error', 'Có lỗi xảy ra: ' . implode(', ', $errors));
        }

        $message = "Đã thêm mới thành công {$imported} ghế vào các vị trí trống!";
        if ($skipped > 0) {
            $message .= " Đã bỏ qua {$skipped} vị trí đã có ghế.";
        }
        if ($imported === 0) {
            $message = "Không có ghế nào được thêm - tất cả vị trí trong file đã có ghế!";
        }

        return back()->with('import_success', $message);
    }

    /**
     * Tạo ghế theo hệ thống mới - từng hàng với loại ghế riêng
     */
    public function storeNew(Request $request)
    {
        \Log::info('storeNew called', [
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'seat_config' => 'required|string'
        ]);

        try {
            $seatConfig = json_decode($request->seat_config, true);
            
            \Log::info('Parsed seat config', [
                'seat_config' => $seatConfig
            ]);

            if (!$seatConfig || !is_array($seatConfig)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu cấu hình ghế không hợp lệ'
                ], 400);
            }

            // Validate từng config
            foreach ($seatConfig as $index => $config) {
                if (!isset($config['row']) || !isset($config['seats_per_row']) || !isset($config['seat_type_id'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Thiếu thông tin cấu hình ghế ở hàng " . ($index + 1)
                    ], 400);
                }
            }

            DB::beginTransaction();

            $room = Room::findOrFail($request->room_id);
            // $seatConfig đã được parse ở trên
            
            \Log::info('Room and config ready', [
                'room_id' => $room->id,
                'room_capacity' => $room->capacity,
                'config_count' => count($seatConfig)
            ]);
            
            // Tính tổng số ghế sẽ tạo
            $totalSeatsToCreate = 0;
            foreach ($seatConfig as $config) {
                $totalSeatsToCreate += $config['seats_per_row'];
            }

            // Kiểm tra sức chứa
            $currentSeats = $room->seats()->count();
            if ($currentSeats + $totalSeatsToCreate > $room->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => "Vượt quá sức chứa phòng. Hiện có {$currentSeats} ghế, muốn thêm {$totalSeatsToCreate} ghế, nhưng sức chứa chỉ {$room->capacity}."
                ], 400);
            }

            $createdSeats = [];
            
            foreach ($seatConfig as $config) {
                $rowCode = $config['row']; // ASCII code
                $seatsPerRow = $config['seats_per_row'];
                $seatTypeId = $config['seat_type_id'];
                
                \Log::info('Processing row config', [
                    'row_ascii_code' => $rowCode,
                    'seats_per_row' => $seatsPerRow,
                    'seat_type_id' => $seatTypeId
                ]);
                
                // Lấy loại ghế để kiểm tra ghế đôi
                $seatType = SeatType::find($seatTypeId);
                $isCoupleType = $this->isCoupleSeaType($seatType->name);
                
                // Nếu là ghế đôi, số ghế phải chẵn
                if ($isCoupleType && $seatsPerRow % 2 !== 0) {
                    $rowChar = chr($rowCode);
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Loại ghế đôi '{$seatType->name}' ở hàng {$rowChar} phải có số lượng ghế chẵn. Hiện tại: {$seatsPerRow}"
                    ], 400);
                }

                // Tạo ghế cho hàng này
                $rowCode = $config['row']; // ASCII code: 68 (D), 69 (E), ...
                $rowChar = chr($rowCode); // Convert ASCII to character: D, E, ...
                
                \Log::info('Row character calculation', [
                    'row_ascii_code' => $rowCode,
                    'row_char' => $rowChar
                ]);
                
                for ($seatNum = 1; $seatNum <= $seatsPerRow; $seatNum++) {
                    // Kiểm tra ghế đã tồn tại chưa
                    $existingSeat = Seat::where('room_id', $room->id)
                        ->where('row_char', $rowChar)
                        ->where('seat_number', $seatNum)
                        ->first();
                    
                    if ($existingSeat) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Ghế {$rowChar}{$seatNum} đã tồn tại trong phòng."
                        ], 400);
                    }

                    $seat = Seat::create([
                        'room_id' => $room->id,
                        'seat_type_id' => $seatTypeId,
                        'row_char' => $rowChar,
                        'seat_number' => $seatNum,
                        'status' => SeatStatus::Available
                    ]);

                    $createdSeats[] = $seat;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đã tạo thành công {$totalSeatsToCreate} ghế!",
                'created_seats' => count($createdSeats),
                'redirect_url' => route('admin.rooms.show', $room->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating seats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo ghế: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thêm ghế đơn lẻ
     */
    public function addSingleSeat(Request $request)
    {
        \Log::info('addSingleSeat called', [
            'request_data' => $request->all()
        ]);

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'row_char' => 'required|string|max:2',
            'seat_number' => 'required|integer|min:1',
            'seat_type_id' => 'required|exists:seat_types,id',
        ]);

        try {
            DB::beginTransaction();

            $room = Room::findOrFail($request->room_id);
            $seatType = SeatType::findOrFail($request->seat_type_id);
            $rowChar = strtoupper($request->row_char);
            $seatNumber = $request->seat_number;

            // Kiểm tra sức chứa phòng
            $currentSeats = $room->seats()->count();
            $isCoupleType = $this->isCoupleSeaType($seatType->name);
            $seatsToAdd = $isCoupleType ? 2 : 1;

            if ($currentSeats + $seatsToAdd > $room->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => "Vượt quá sức chứa phòng. Hiện có {$currentSeats} ghế, muốn thêm {$seatsToAdd} ghế, nhưng sức chứa chỉ {$room->capacity}."
                ], 400);
            }

            // Kiểm tra ghế đã tồn tại chưa
            $existingSeat = Seat::where('room_id', $room->id)
                ->where('row_char', $rowChar)
                ->where('seat_number', $seatNumber)
                ->first();

            if ($existingSeat) {
                return response()->json([
                    'success' => false,
                    'message' => "Ghế {$rowChar}{$seatNumber} đã tồn tại trong phòng."
                ], 400);
            }

            $createdSeats = [];

            // Nếu là ghế đôi, phải đảm bảo số ghế lẻ và có thể tạo cặp
            if ($isCoupleType) {
                if ($seatNumber % 2 === 0) {
                    return response()->json([
                        'success' => false,
                        'message' => "Ghế đôi phải bắt đầu từ số lẻ (ví dụ: {$rowChar}1, {$rowChar}3, {$rowChar}5...)"
                    ], 400);
                }

                $partnerSeatNumber = $seatNumber + 1;

                // Kiểm tra ghế đôi partner đã tồn tại chưa
                $existingPartner = Seat::where('room_id', $room->id)
                    ->where('row_char', $rowChar)
                    ->where('seat_number', $partnerSeatNumber)
                    ->first();

                if ($existingPartner) {
                    return response()->json([
                        'success' => false,
                        'message' => "Ghế partner {$rowChar}{$partnerSeatNumber} đã tồn tại. Không thể tạo ghế đôi."
                    ], 400);
                }

                // Tạo cả 2 ghế đôi
                $seat1 = Seat::create([
                    'room_id' => $room->id,
                    'seat_type_id' => $seatType->id,
                    'row_char' => $rowChar,
                    'seat_number' => $seatNumber,
                    'status' => SeatStatus::Available
                ]);

                $seat2 = Seat::create([
                    'room_id' => $room->id,
                    'seat_type_id' => $seatType->id,
                    'row_char' => $rowChar,
                    'seat_number' => $partnerSeatNumber,
                    'status' => SeatStatus::Available
                ]);

                $createdSeats = [$seat1, $seat2];
                $message = "Đã thêm thành công ghế đôi {$rowChar}{$seatNumber}-{$rowChar}{$partnerSeatNumber}!";

            } else {
                // Tạo ghế đơn
                $seat = Seat::create([
                    'room_id' => $room->id,
                    'seat_type_id' => $seatType->id,
                    'row_char' => $rowChar,
                    'seat_number' => $seatNumber,
                    'status' => SeatStatus::Available
                ]);

                $createdSeats = [$seat];
                $message = "Đã thêm thành công ghế {$rowChar}{$seatNumber}!";
            }

            DB::commit();

            \Log::info('Single seat(s) added successfully', [
                'created_seats' => count($createdSeats),
                'seat_type' => $seatType->name,
                'is_couple' => $isCoupleType
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'created_seats' => count($createdSeats),
                'seats' => collect($createdSeats)->map(function($seat) {
                    return [
                        'id' => $seat->id,
                        'row' => $seat->row_char,
                        'seat_number' => str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT),
                        'seat_type_id' => $seat->seat_type_id,
                        'seat_type_name' => $seat->seatType->name,
                        'price' => $seat->seatType->price,
                        'position' => $seat->row_char . $seat->seat_number,
                        'type' => $seat->seatType->name
                    ];
                })->toArray(),
                // Trả về ghế đầu tiên cho frontend (trong trường hợp ghế đơn)
                'seat' => count($createdSeats) > 0 ? [
                    'id' => $createdSeats[0]->id,
                    'row' => $createdSeats[0]->row_char,
                    'seat_number' => str_pad($createdSeats[0]->seat_number, 2, '0', STR_PAD_LEFT),
                    'seat_type_id' => $createdSeats[0]->seat_type_id,
                    'seat_type_name' => $createdSeats[0]->seatType->name,
                    'price' => $createdSeats[0]->seatType->price
                ] : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding single seat: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm ghế: ' . $e->getMessage()
            ], 500);
        }
    }
}
