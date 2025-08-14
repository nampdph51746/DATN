<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;
use App\Enums\SeatStatus;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
<<<<<<< Updated upstream
use Illuminate\Support\Facades\Auth;
=======

>>>>>>> Stashed changes
use App\Models\RoomSeatConfiguration;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        Log::info('---[START STORE SEAT]---');
        Log::info('Request data: ', $request->all());

<<<<<<< Updated upstream
        $rules = [
            'seat_type_id' => 'required|exists:seat_types,id',
            'min_seats_per_row' => 'required|integer|min:1|max:50',
            'seat_type_percentages' => 'required|array',
            'seat_type_percentages.*' => 'required|numeric|min:0|max:100',
            'seats_per_row' => 'required|integer|min:1|max:50', // Thêm rule cho seats_per_row
        ];

        $validator = Validator::make($request->all(), $rules, [
            'room_id.required' => 'Phòng chiếu là bắt buộc.',
            'room_id.exists' => 'Phòng chiếu không tồn tại.',
            'seat_type_id.required' => 'Loại ghế là bắt buộc.',
            'seat_type_id.exists' => 'Loại ghế không tồn tại.',
            'min_seats_per_row.required' => 'Số ghế tối thiểu mỗi hàng là bắt buộc.',
            'min_seats_per_row.integer' => 'Số ghế tối thiểu mỗi hàng phải là số nguyên.',
            'min_seats_per_row.min' => 'Số ghế tối thiểu mỗi hàng phải lớn hơn hoặc bằng 1.',
            'min_seats_per_row.max' => 'Số ghế tối thiểu mỗi hàng không được vượt quá 50.',
            'seat_type_percentages.required' => 'Tỷ lệ loại ghế là bắt buộc.',
            'seat_type_percentages.*.required' => 'Tỷ lệ mỗi loại ghế không được để trống.',
            'seat_type_percentages.*.numeric' => 'Tỷ lệ phải là số.',
            'seat_type_percentages.*.min' => 'Tỷ lệ phải lớn hơn hoặc bằng 0.',
            'seat_type_percentages.*.max' => 'Tỷ lệ phải nhỏ hơn hoặc bằng 100.',
            'seats_per_row.required' => 'Số ghế mỗi hàng là bắt buộc.',
            'seats_per_row.integer' => 'Số ghế mỗi hàng phải là số nguyên.',
            'seats_per_row.min' => 'Số ghế mỗi hàng phải lớn hơn hoặc bằng 1.',
            'seats_per_row.max' => 'Số ghế mỗi hàng không được vượt quá 50.',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $room = Room::findOrFail($request->input('room_id')); // Sử dụng findOrFail
        Log::info('Room: ', $room->toArray());
        if ($room->status !== 'active') {
            $validator->errors()->add('room_id', 'Phòng chiếu không ở trạng thái hoạt động.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $roomSeatValidation = new \App\Services\RoomSeatValidationService();
        Log::info('RoomSeatValidationService created');
        
        $isAllowed = $roomSeatValidation->isSeatTypeAllowedInRoom($room, $request->input('seat_type_id'));
        Log::info('isSeatTypeAllowedInRoom: ', [$request->input('seat_type_id'), $isAllowed]);
        if (!$isAllowed) {
            $seatType = SeatType::find($request->input('seat_type_id'));
            $validator->errors()->add('seat_type_id', 
                "Loại ghế '{$seatType->name}' không được phép trong phòng {$room->roomType->name}.");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $percentageErrors = $roomSeatValidation->validateSeatPercentages($room, $request->input('seat_type_percentages'));
        Log::info('validateSeatPercentages errors: ', $percentageErrors);
        if (!empty($percentageErrors)) {
            foreach ($percentageErrors as $error) {
                $validator->errors()->add('seat_type_percentages', $error);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingSeatsCount = Seat::where('room_id', $room->id)->count();
        Log::info('existingSeatsCount: ' . $existingSeatsCount);
        if ($existingSeatsCount >= $room->capacity) {
            $validator->errors()->add('room_id', 'Phòng đã đầy ghế.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        Log::info('Begin DB transaction');
        try {
            RoomSeatConfiguration::where('room_id', $room->id)->delete();
            Log::info('Deleted old RoomSeatConfiguration');
            foreach ($request->input('seat_type_percentages') as $seatTypeId => $percentage) {
                Log::info("Config seatTypeId=$seatTypeId, percentage=$percentage");
                if ($percentage > 0) {
                    RoomSeatConfiguration::create([
                        'room_id' => $room->id,
                        'seat_type_id' => $seatTypeId,
                        'percentage' => $percentage,
                    ]);
                    Log::info("Created RoomSeatConfiguration for seatTypeId=$seatTypeId");
                }
            }

            $seatTypes = SeatType::all()->keyBy('id');
            Log::info('seatTypes: ', $seatTypes->map(function($seatType) { return ['id' => $seatType->id, 'name' => $seatType->name]; })->toArray());
            $requiredSeats = [];
            $totalSeats = $room->capacity;
            foreach ($request->input('seat_type_percentages') as $seatTypeId => $percentage) {
                $requiredSeats[$seatTypeId] = (int) round(($percentage / 100) * $totalSeats);
                Log::info("Required seats for seatTypeId=$seatTypeId: " . $requiredSeats[$seatTypeId]);
            }

            $existingSeatsByType = Seat::where('room_id', $room->id)
                ->select('seat_type_id', DB::raw('count(*) as count'))
                ->groupBy('seat_type_id')
                ->pluck('count', 'seat_type_id')
                ->toArray();
            Log::info('existingSeatsByType: ', $existingSeatsByType);

            $currentTypeSeats = $existingSeatsByType[$request->input('seat_type_id')] ?? 0;
            $requiredTypeSeats = $requiredSeats[$request->input('seat_type_id')] ?? 0;
            $remainingCapacity = $room->capacity - $existingSeatsCount;
            $totalSeatsToAdd = min($requiredTypeSeats - $currentTypeSeats, $remainingCapacity);
            Log::info("currentTypeSeats=$currentTypeSeats, requiredTypeSeats=$requiredTypeSeats, remainingCapacity=$remainingCapacity, totalSeatsToAdd=$totalSeatsToAdd");

            $maxRows = 26;
            $maxSeatsPerRow = 50;
            $seatsPerRow = (int) $request->input('seats_per_row');
            if ($seatsPerRow < 1 || $seatsPerRow > $maxSeatsPerRow) {
                $validator->errors()->add('seats_per_row', 'Số ghế mỗi hàng phải từ 1 đến ' . $maxSeatsPerRow . '.');
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $rows = ceil($totalSeatsToAdd / $seatsPerRow);
            Log::info("seatsPerRow=$seatsPerRow, rows=$rows");

            $totalSeatsProposed = $rows * $seatsPerRow;
            Log::info("totalSeatsProposed=$totalSeatsProposed");
            $excessSeats = 0;
            $warningMsg = '';
            if ($totalSeatsProposed > $totalSeatsToAdd) {
                $excessSeats = $totalSeatsProposed - $totalSeatsToAdd;
                Log::warning("Excess seats: $excessSeats");
                $warningMsg = "Cảnh báo: Số ghế/hàng bạn chọn sẽ dư $excessSeats ghế so với số ghế cần thêm cho loại này. Hệ thống chỉ thêm đúng số ghế cần thiết.";
            }
            if ($totalSeatsProposed > $remainingCapacity) {
                Log::warning("Seats to add exceed remainingCapacity");
                $warningMsg .= " Số ghế muốn thêm vượt quá sức chứa còn lại ($remainingCapacity ghế). Hệ thống chỉ thêm đúng số ghế còn lại.";
            }

            $existingSeats = Seat::where('room_id', $room->id)
                ->get()
                ->groupBy('row_char')
                ->map->pluck('seat_number')
                ->toArray();
            Log::info('existingSeats: ', $existingSeats);

            $previewSeats = [];
            $createdSeats = 0;
            $maxRowChar = Seat::where('room_id', $room->id)->max('row_char');
            $startRowIndex = $maxRowChar ? ord(strtoupper($maxRowChar)) - 64 : 0;
            Log::info("maxRowChar=$maxRowChar, startRowIndex=$startRowIndex");

            $createdSeatIds = [];
            for ($i = 0; $i < $rows; $i++) {
                $rowChar = chr(65 + $startRowIndex + $i);
                Log::info("Row $i: rowChar=$rowChar");
                if (strlen($rowChar) > 5) {
                    Log::error('Row char length exceeded: ' . $rowChar);
                    throw new \Exception('Số hàng vượt quá giới hạn ký tự cho phép.');
                }
                for ($j = 1; $j <= $seatsPerRow; $j++) {
                    if ($createdSeats >= $totalSeatsToAdd) {
                        break 2;
                    }
                    $seatNumber = str_pad($j, 2, '0', STR_PAD_LEFT);
                    Log::info("Trying seat $rowChar$seatNumber");
                    if (isset($existingSeats[$rowChar]) && in_array($seatNumber, $existingSeats[$rowChar])) {
                        Log::warning("Seat $rowChar$seatNumber already exists, skipping.");
                        continue;
                    }
                    $seat = Seat::create([
                        'room_id' => $room->id,
                        'seat_type_id' => $request->input('seat_type_id'),
                        'row_char' => $rowChar,
                        'seat_number' => $seatNumber,
                        'status' => SeatStatus::Available->value ?? 'available',
                    ]);
                    Log::info("Created seat: $rowChar$seatNumber", $seat->toArray());
                    $createdSeats++;
                    $createdSeatIds[] = $seat->id;
                    $previewSeats[] = [
                        'row_char' => $rowChar,
                        'seat_number' => $seatNumber,
                        'seat_type_id' => $request->input('seat_type_id'),
                    ];
                    if ($existingSeatsCount + $createdSeats > $room->capacity) {
                        Log::error('Số ghế vượt quá sức chứa của phòng!');
                        throw new \Exception('Số ghế vượt quá sức chứa của phòng (' . $room->capacity . ' ghế).');
                    }
                }
            }
            Log::info("Total createdSeats=$createdSeats");

            // Tạo thông báo khi thêm ghế
            if ($createdSeats > 0) {
                Notification::create([
                    'user_id' => Auth::id(),
                    'entity_type' => Seat::class,
                    'entity_id' => implode(',', $createdSeatIds),
                    'title' => 'Thêm mới ghế',
                    'message' => $createdSeats . ' ghế mới đã được thêm vào phòng #' . $room->id,
                    'type' => NotificationType::System,
                    'priority' => 'high',
                    'old_status' => null,
                    'new_status' => null,
                    'event_details' => json_encode([
                        'room_id' => $room->id,
                        'seat_type_id' => $request->input('seat_type_id'),
                        'created_seat_ids' => $createdSeatIds,
                    ]),
                ]);
            }

            // Cập nhật existingSeatsByType trước khi lưu vào session
            $existingSeatsByType[$request->input('seat_type_id')] = ($existingSeatsByType[$request->input('seat_type_id')] ?? 0) + $createdSeats;

            $seatTypesOrder = array_keys($requiredSeats);
            $currentTypeIndex = array_search($request->input('seat_type_id'), $seatTypesOrder);
            $nextSeatTypeId = null;
            if ($currentTypeIndex !== false && $currentTypeIndex + 1 < count($seatTypesOrder)) {
                $nextSeatTypeId = $seatTypesOrder[$currentTypeIndex + 1];
            }
            Log::info("nextSeatTypeId=$nextSeatTypeId");

            $nextSuggestedSeatsPerRow = null;
            $nextTotalSeatsToAdd = null;
            if ($nextSeatTypeId) {
                $nextCurrentTypeSeats = $existingSeatsByType[$nextSeatTypeId] ?? 0;
                $nextRequiredTypeSeats = $requiredSeats[$nextSeatTypeId] ?? 0;
                $nextTotalSeatsToAdd = min($nextRequiredTypeSeats - $nextCurrentTypeSeats, $remainingCapacity - $createdSeats);
                for ($i = $request->input('min_seats_per_row'); $i <= $maxSeatsPerRow; $i++) {
                    if ($nextTotalSeatsToAdd / $i <= $maxRows) {
                        $nextSuggestedSeatsPerRow = $i;
                        break;
                    }
                }
            }
            $suggestionMsg = $nextSuggestedSeatsPerRow
                ? "Đề xuất số ghế mỗi hàng cho lần thêm tiếp theo: $nextSuggestedSeatsPerRow"
                : "Không có đề xuất số ghế mỗi hàng tối ưu cho lần thêm tiếp theo.";

            DB::commit();

            Log::info('---[END STORE SEAT]---');
            session([
                'success' => "$createdSeats ghế loại '{$seatTypes[$request->input('seat_type_id')]->name}' đã được thêm thành công! $suggestionMsg" . ($warningMsg ? "\n$warningMsg" : ''),
                'next_seat_type_id' => $nextSeatTypeId,
                'seat_data' => [
                    'existingSeatsByType' => $existingSeatsByType,
                    'requiredSeats' => $requiredSeats,
                    'remainingCapacity' => $remainingCapacity - $createdSeats,
                    'currentTypeSeats' => $currentTypeSeats,
                    'requiredTypeSeats' => $requiredTypeSeats,
                    'totalSeatsToAdd' => $totalSeatsToAdd - $createdSeats,
                    'previewSeats' => $previewSeats,
                    'nextSuggestedSeatsPerRow' => $nextSuggestedSeatsPerRow,
                    'nextTotalSeatsToAdd' => $nextTotalSeatsToAdd,
                ]
            ]);
            return redirect()->route('admin.rooms.show', ['room' => $room->id]);

        } catch (\Exception $e) {
            Log::error('Exception during seat creation: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi thêm ghế: ' . $e->getMessage())->withInput();
=======
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
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
        $seat = Seat::findOrFail($id);
        $oldData = $seat->getOriginal();
=======
        $seat = Seat::with('seatType')->findOrFail($id);
>>>>>>> Stashed changes

        $request->validate([
            'seat_type_id' => 'required|exists:seat_types,id',
            'status' => 'required|in:available,reserved,booked',
        ]);

<<<<<<< Updated upstream
        $seat->update([
            'seat_type_id' => $request->input('seat_type_id'),
            'status' => $request->input('status'),
        ]);

        // Tạo thông báo khi cập nhật ghế
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Seat::class,
            'entity_id' => $seat->id,
            'title' => 'Cập nhật ghế',
            'message' => 'Ghế ' . $seat->row_char . $seat->seat_number . ' đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'high',
            'old_status' => $oldData['status'] ?? null,
            'new_status' => $seat->status,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $seat->getAttributes(),
            ]),
        ]);

        $roomId = $seat->room_id;
        return redirect()->route('admin.rooms.show', $roomId)->with('success', 'Ghế đã được cập nhật thành công!');
    }

    public function show($id)
    {
        Log::info('Session data:', session()->all());
        $room = Room::with(['cinema', 'roomType'])->findOrFail($id);
        Log::info('Room type: ' . get_class($room));
        Log::info('Room value: ', $room->toArray());
        $allowedSeatTypes = method_exists($room, 'allowedSeatTypes') ? $room->allowedSeatTypes() : [];
        $seats = Seat::with('seatType')
            ->where('room_id', $room->id)
            ->orderBy('row_char')
            ->orderBy('seat_number')
            ->get();
        $rows = $seats->pluck('row_char')->unique()->sort()->values();
        $maxSeatsPerRow = $seats->isEmpty() ? 50 : $seats->groupBy('row_char')->map(function($group) { return $group->count(); })->max();
        $maxRows = 26;

        $seatPercentages = RoomSeatConfiguration::where('room_id', $room->id)
            ->pluck('percentage', 'seat_type_id')
            ->toArray();

        $existingSeatsByType = $seats->groupBy('seat_type_id')->mapWithKeys(function($group, $key) {
            return [$key => $group->count()];
        })->toArray();
        $requiredSeats = [];
        foreach ($seatPercentages as $seatTypeId => $percentage) {
            $requiredSeats[$seatTypeId] = (int) round(($percentage / 100) * $room->capacity);
        }
        $remainingCapacity = $room->capacity - $seats->count();
        $currentTypeSeats = null;
        $requiredTypeSeats = null;
        $totalSeatsToAdd = null;
        $nextSuggestedSeatsPerRow = null;
        $nextTotalSeatsToAdd = null;
        $previewSeats = [];
        if (session()->has('seat_data')) {
            $seatData = session('seat_data');
            if (isset($seatData['existingSeatsByType'])) $existingSeatsByType = $seatData['existingSeatsByType'];
            if (isset($seatData['requiredSeats'])) $requiredSeats = $seatData['requiredSeats'];
            if (isset($seatData['remainingCapacity'])) $remainingCapacity = $seatData['remainingCapacity'];
            if (isset($seatData['currentTypeSeats'])) $currentTypeSeats = $seatData['currentTypeSeats'];
            if (isset($seatData['requiredTypeSeats'])) $requiredTypeSeats = $seatData['requiredTypeSeats'];
            if (isset($seatData['totalSeatsToAdd'])) $totalSeatsToAdd = $seatData['totalSeatsToAdd'];
            if (isset($seatData['nextSuggestedSeatsPerRow'])) $nextSuggestedSeatsPerRow = $seatData['nextSuggestedSeatsPerRow'];
            if (isset($seatData['nextTotalSeatsToAdd'])) $nextTotalSeatsToAdd = $seatData['nextTotalSeatsToAdd'];
            if (isset($seatData['previewSeats'])) $previewSeats = $seatData['previewSeats'];
        }

        $seatTypesOrder = array_keys($seatPercentages);
        $next_seat_type_id = null;
        if (!empty($seatTypesOrder)) {
            $currentTypeIndex = session()->has('next_seat_type_id') ? array_search(session('next_seat_type_id'), $seatTypesOrder) : 0;
            if ($currentTypeIndex === false) {
                $currentTypeIndex = 0;
            }
            $next_seat_type_id = $seatTypesOrder[$currentTypeIndex];
        }

        return view('admin.rooms.show', compact(
            'room', 'allowedSeatTypes', 'seats', 'rows', 'maxSeatsPerRow', 'maxRows',
            'seatPercentages', 'existingSeatsByType', 'requiredSeats', 'next_seat_type_id', 'seatTypesOrder',
            'remainingCapacity', 'currentTypeSeats', 'requiredTypeSeats', 'totalSeatsToAdd',
            'nextSuggestedSeatsPerRow', 'nextTotalSeatsToAdd', 'previewSeats'
        ));
    }

    public function editBulk(Request $request)
    {
        $seatIds = $request->input('seat_ids', []);
        
        if (empty($seatIds)) {
            return redirect()->route('admin.seats.index')->with('error', 'Vui lòng chọn ít nhất một ghế để chỉnh sửa.');
        }

        $seats = Seat::with('room', 'seatType')->whereIn('id', $seatIds)->get();
        $seatTypes = SeatType::all();

        return view('admin.seats.edit-bulk', compact('seats', 'seatTypes'));
=======
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
>>>>>>> Stashed changes
    }

    public function destroy($id)
    {
<<<<<<< Updated upstream
        $request->validate([
            'status' => 'nullable|string|in:available,booked,sold,broken',
        ]);
        $seatIds = $request->input('seat_ids', []);
        $seatTypeId = $request->input('seat_type_id');
        $status = $request->input('status');

        if (empty($seatIds)) {
            return redirect()->route('admin.seats.index')->with('error', 'Không có ghế nào được chọn để cập nhật.');
        }

        DB::transaction(function () use ($seatIds, $seatTypeId, $status) {
            foreach ($seatIds as $seatId) {
                $seat = Seat::find($seatId);
                if ($seat) {
                    if ($seatTypeId) {
                        $seat->seat_type_id = $seatTypeId;
                    }
                    if ($status) {
                        $seat->status = $status;
                    }
                    $seat->save();
                }
            }
        });

        $firstSeat = Seat::find($seatIds[0]);
        $roomId = ($firstSeat && !empty($firstSeat->room_id)) ? $firstSeat->room_id : null;
        Log::info('Redirect roomId: ' . print_r($roomId, true));
        if ($roomId && is_numeric($roomId) && $roomId > 0) {
            return redirect()->route('admin.rooms.show', ['room' => $roomId])->with('success', 'Cập nhật hàng loạt ghế thành công!');
        }
        return redirect()->route('admin.seats.index')->with('success', 'Cập nhật hàng loạt ghế thành công!');
    }

    public function importExcel(Request $request)
=======
        $seat = Seat::findOrFail($id);
        $roomId = $seat->room_id;
        $seat->delete();

        return redirect()->route('admin.rooms.show', ['room' => $roomId])->with('success', 'Xóa ghế thành công!');
    }

    public function bulkDestroy(Request $request, Room $room)
>>>>>>> Stashed changes
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        try {
<<<<<<< Updated upstream
            $file = $request->file('excel_file');
            $filePath = $file->getRealPath();
            $extension = strtolower($file->getClientOriginalExtension());
            $rows = [];
            if (in_array($extension, ['xlsx', 'xls'])) {
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
            } elseif ($extension === 'csv') {
                if (($handle = fopen($filePath, 'r')) !== false) {
                    while (($data = fgetcsv($handle)) !== false) {
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
            } else {
                return back()->with('import_error', 'Định dạng file không hợp lệ.');
            }

            if (empty($rows) || count($rows) < 2) {
                return back()->with('import_error', 'File không có dữ liệu ghế.');
            }
            $header = $rows[0];
            $expectedHeader = ['row_char', 'seat_number', 'seat_type', 'status'];
            $headerLower = array_map('strtolower', $header);
            if (array_diff($expectedHeader, $headerLower)) {
                return back()->with('import_error', 'File phải có các cột: row_char, seat_number, seat_type, status');
            }

            $allowedSeatTypes = $room->allowedSeatTypes();
            $allowedSeatTypeNames = $allowedSeatTypes->pluck('name', 'id')->map(function($name){ return strtolower($name); })->toArray();
            $allowedSeatTypeNameToId = array_flip($allowedSeatTypeNames);

            $imported = 0;
            $errors = [];
            $seatsToImport = [];
            foreach (array_slice($rows, 1) as $rowIndex => $row) {
                $rowChar = strtoupper(trim($row[0] ?? ''));
                $seatNumber = str_pad(trim($row[1] ?? ''), 2, '0', STR_PAD_LEFT);
                $seatTypeName = strtolower(trim($row[2] ?? ''));
                $status = strtolower(trim($row[3] ?? 'available'));

                if (!$rowChar || !$seatNumber || !$seatTypeName) {
                    $errors[] = "Dòng " . ($rowIndex+2) . ": thiếu thông tin bắt buộc.";
                    continue;
                }
                if (!in_array($seatTypeName, $allowedSeatTypeNames)) {
                    $errors[] = "Dòng " . ($rowIndex+2) . ": loại ghế không hợp lệ.";
                    continue;
                }
                $seatTypeId = $allowedSeatTypeNameToId[$seatTypeName];
                if (!in_array($status, ['available','reserved','booked'])) {
                    $errors[] = "Dòng " . ($rowIndex+2) . ": trạng thái không hợp lệ.";
                    continue;
                }

                $exists = Seat::where('room_id', $room->id)
                    ->where('row_char', $rowChar)
                    ->where('seat_number', $seatNumber)
                    ->exists();
                if ($exists) {
                    $errors[] = "Dòng " . ($rowIndex+2) . ": ghế đã tồn tại.";
                    continue;
                }

                $seatsToImport[] = [
                    'room_id' => $room->id,
                    'seat_type_id' => $seatTypeId,
                    'row_char' => $rowChar,
                    'seat_number' => $seatNumber,
                    'status' => $status,
                ];
            }

            $currentSeatsCount = Seat::where('room_id', $room->id)->count();
            $roomCapacity = $room->capacity;
            $remainingCapacity = $roomCapacity - $currentSeatsCount;
            if (count($seatsToImport) > $remainingCapacity) {
                return back()->with('import_error', 'Số lượng ghế trong file vượt quá sức chứa còn lại của phòng (' . $remainingCapacity . ' ghế).');
            }

            foreach ($seatsToImport as $seatData) {
                Seat::create($seatData);
                $imported++;
            }

            if ($imported > 0) {
                $msg = "Đã import thành công $imported ghế.";
                if ($errors) $msg .= " Một số dòng bị bỏ qua: " . implode(' | ', $errors);
                return back()->with('import_success', $msg);
            } else {
                return back()->with('import_error', 'Không import được ghế nào. ' . implode(' | ', $errors));
            }

=======
            DB::beginTransaction();
            
            $deletedCount = Seat::whereIn('id', $request->seat_ids)
                ->where('room_id', $room->id)
                ->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.rooms.show', $room->id)
                ->with('success', "Đã xóa thành công {$deletedCount} ghế!");
                
>>>>>>> Stashed changes
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
