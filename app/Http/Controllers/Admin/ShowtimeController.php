<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ShowtimeStatusService;

use App\Services\ShowtimePricingService;

class ShowtimeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view role')->only('index');
        $this->middleware('can:create role')->only(['create', 'store']);
        $this->middleware('can:edit role')->only(['edit', 'update']);
        $this->middleware('can:delete role')->only('destroy');
        $this->middleware('update.showtime.status')->only(['index', 'show']);
    }
    
    public function index(Request $request)
    {
        // Tự động cập nhật trạng thái suất chiếu khi load trang
        try {
            $service = new ShowtimeStatusService();
            $updatedCount = $service->updateShowtimeStatuses();
            
            if ($updatedCount > 0) {
                Log::info("Tự động cập nhật trạng thái cho {$updatedCount} suất chiếu khi load trang index");
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi tự động cập nhật trạng thái suất chiếu: ' . $e->getMessage());
        }

        $query = $request->input('query');
        $movieId = $request->input('movie_id');
        $roomId = $request->input('room_id');
        $status = $request->input('status', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $showtimes = Showtime::with(['movie', 'room']) 
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->whereHas('movie', function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%");
                })->orWhereHas('room', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->orWhere('start_time', 'like', "%{$query}%");
            })
            ->when($movieId, function ($queryBuilder) use ($movieId) {
                return $queryBuilder->where('movie_id', $movieId);
            })
            ->when($roomId, function ($queryBuilder) use ($roomId) {
                return $queryBuilder->where('room_id', $roomId);
            })
            ->when($status !== 'all', function ($queryBuilder) use ($status) {
                return $queryBuilder->where('status', $status);
            })
            ->when($startDate, function ($queryBuilder) use ($startDate) {
                return $queryBuilder->where('start_time', '>=', $startDate);
            })
            ->when($endDate, function ($queryBuilder) use ($endDate) {
                return $queryBuilder->where('end_time', '<=', $endDate . ' 23:59:59');
            })
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function show($id)
    {
        // Tự động cập nhật trạng thái suất chiếu khi xem chi tiết
        try {
            $service = new ShowtimeStatusService();
            $updatedCount = $service->updateShowtimeStatuses();
            
            if ($updatedCount > 0) {
                Log::info("Tự động cập nhật trạng thái cho {$updatedCount} suất chiếu khi xem chi tiết");
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi tự động cập nhật trạng thái suất chiếu: ' . $e->getMessage());
        }

        $showtime = Showtime::with(['movie', 'room'])->findOrFail($id);
        $ticketCount = \App\Models\Ticket::where('showtime_id', $id)->count();
        return view('admin.showtimes.show', compact('showtime', 'ticketCount'));
    }

    public function edit($id)
    {
        $showtime = Showtime::findOrFail($id);
        $movies = \App\Models\Movie::all();
        $rooms = \App\Models\Room::all();
        return view('admin.showtimes.edit', compact('showtime', 'movies', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $showtime = Showtime::findOrFail($id);

        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => [
                'required',
                'date',
                'after:now+1hour',
                function ($attribute, $value, $fail) use ($request, $showtime) {
                    $conflictingShowtime = Showtime::where('room_id', $request->room_id)
                        ->where('id', '!=', $showtime->id)
                        ->where(function ($q) use ($request) {
                            $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                            ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                            ->orWhere(function ($q) use ($request) {
                                $q->where('start_time', '<=', $request->start_time)
                                    ->where('end_time', '>=', $request->end_time);
                            });
                        })
                        ->first();

                    if ($conflictingShowtime) {
                        $fail('Phòng chiếu đã có suất chiếu khác trong khoảng thời gian này.');
                    }
                },
            ],
            'end_time' => [
                'required',
                'date',
            ],
            'base_price' => 'required|numeric|min:0|max:99999.99',
            'status' => [
                'required',
                'in:scheduled,ongoing,completed,cancelled,postponed',
                function ($attribute, $value, $fail) use ($request, $showtime) {
                    $start_time = \Carbon\Carbon::parse($request->start_time);
                    $end_time = \Carbon\Carbon::parse($request->end_time);
                    $now = now();

                    if ($value === 'scheduled' && $start_time->isPast()) {
                        $fail('Không thể đặt trạng thái "scheduled" cho suất chiếu đã bắt đầu.');
                    }
                    if ($value === 'ongoing' && ($start_time->isFuture() || $end_time->isPast())) {
                        $fail('Trạng thái "ongoing" chỉ hợp lệ khi suất chiếu đang diễn ra.');
                    }
                    if ($value === 'completed' && !$end_time->isPast()) {
                        $fail('Trạng thái "completed" chỉ hợp lệ khi suất chiếu đã kết thúc.');
                    }
                    if ($value === 'postponed' && $start_time->isPast()) {
                        $fail('Không thể hoãn suất chiếu đã bắt đầu.');
                    }
                },
            ],
        ], [
            'movie_id.required' => 'Phim là bắt buộc.',
            'movie_id.exists' => 'Phim không tồn tại.',
            'room_id.required' => 'Phòng chiếu là bắt buộc.',
            'room_id.exists' => 'Phòng chiếu không tồn tại.',
            'start_time.required' => 'Thời gian bắt đầu là bắt buộc.',
            'start_time.date' => 'Thời gian bắt đầu không hợp lệ.',
            'start_time.after' => 'Thời gian bắt đầu phải cách hiện tại ít nhất 1 giờ.',
            'end_time.required' => 'Thời gian kết thúc là bắt buộc.',
            'end_time.date' => 'Thời gian kết thúc không hợp lệ.',
            'base_price.required' => 'Giá vé là bắt buộc.',
            'base_price.numeric' => 'Giá vé phải là số.',
            'base_price.min' => 'Giá vé không được nhỏ hơn 0.',
            'base_price.max' => 'Giá vé không được vượt quá 99,999.99 VNĐ.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        try {
            \Log::info('Raw Start Time: ' . $request->start_time . ', Raw End Time: ' . $request->end_time);

            $start = \Carbon\Carbon::parse($request->start_time, 'Asia/Ho_Chi_Minh')->setSeconds(0);
            $end = \Carbon\Carbon::parse($request->end_time, 'Asia/Ho_Chi_Minh')->setSeconds(0);

            \Log::info('Start Timezone: ' . $start->timezone->getName() . ', End Timezone: ' . $end->timezone->getName());

            $startDate = $start->toDateString();
            $endDate = $end->toDateString();
            if ($end->format('H:i') === '00:30' && $startDate === $endDate) {
                $end->addDay();
            }

            $duration = abs($end->diffInMinutes($start));
            \Log::info('Start Time: ' . $start->toDateTimeString() . ', End Time: ' . $end->toDateTimeString() . ', Duration: ' . $duration . ' minutes');

            if ($end->lessThan($start)) {
                return redirect()->back()->with('error', 'Thời gian kết thúc phải sau thời gian bắt đầu.');
            }

            $movie = \App\Models\Movie::findOrFail($request->movie_id);
            $movieDuration = $movie->duration_minutes; // Đồng bộ với duration_minutes
            $minDuration = $movieDuration + 15; 

            if ($duration < $minDuration) {
                return redirect()->back()->with('error', 'Thời lượng suất chiếu phải dài hơn hoặc bằng ' . $minDuration . ' phút (bao gồm độ dài phim ' . $movieDuration . ' phút và 15 phút đệm).');
            }

            if ($duration > 180) {
                return redirect()->back()->with('error', 'Thời lượng suất chiếu không được vượt quá 3 giờ.');
            }

            // Kiểm tra xem có thể chỉnh sửa không
            $hasConfirmedTickets = $showtime->tickets()->whereHas('booking', function($query) {
                $query->where('status', 'confirmed');
            })->exists();

            // Không cho phép sửa các thông tin quan trọng nếu đã có vé được đặt và xác nhận
            if ($hasConfirmedTickets && (
                $showtime->movie_id != $request->movie_id ||
                $showtime->room_id != $request->room_id ||
                $showtime->start_time->format('Y-m-d H:i:s') != $start->format('Y-m-d H:i:s') ||
                $showtime->end_time->format('Y-m-d H:i:s') != $end->format('Y-m-d H:i:s')
            )) {
                $ticketCount = $showtime->tickets()->whereHas('booking', function($query) {
                    $query->where('status', 'confirmed');
                })->count();
                
                return redirect()->back()->with('error', "Không thể chỉnh sửa phim, phòng chiếu, hoặc thời gian vì đã có {$ticketCount} vé được đặt và xác nhận. Chỉ có thể điều chỉnh giá vé và trạng thái.");
            }

            // Nếu có vé đã đặt, chỉ cho phép điều chỉnh một số trường nhất định
            $updateData = [];
            
            if ($hasConfirmedTickets) {
                // Chỉ cho phép cập nhật giá và trạng thái khi có vé đã đặt
                $updateData = [
                    'base_price' => $request->base_price,
                    'status' => $request->status,
                ];
                
                // Thông báo cho người dùng biết
                session()->flash('info', 'Do đã có vé được đặt, chỉ có thể điều chỉnh giá vé và trạng thái.');
            } else {
                // Có thể cập nhật tất cả nếu chưa có vé nào được đặt
                $updateData = [
                    'movie_id' => $request->movie_id,
                    'room_id' => $request->room_id,
                    'start_time' => $start->format('Y-m-d H:i:s'),
                    'end_time' => $end->format('Y-m-d H:i:s'),
                    'base_price' => $request->base_price,
                    'status' => $request->status,
                ];
            }

            $showtime->update($updateData);

            return redirect()->route('admin.showtimes.index')->with('success', 'Cập nhật suất chiếu thành công!');
        } catch (\Exception $e) {
            \Log::error('Lỗi khi cập nhật suất chiếu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật suất chiếu. Vui lòng thử lại.');
        }
    }

    public function storeAuto(Request $request)
    {
        Log::info('Nhận request storeAuto: ' . json_encode($request->all()));

        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $selectedDate = Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh');
                    $now = Carbon::now('Asia/Ho_Chi_Minh');
                    
                    // Nếu chọn ngày hiện tại, kiểm tra có đủ thời gian để tạo suất chiếu không
                    if ($selectedDate->isSameDay($now)) {
                        // Tính thời gian bắt đầu sớm nhất (hiện tại + 30 phút buffer)
                        $earliestStart = $now->copy()->addMinutes(30);
                        $endOfDay = $selectedDate->copy()->setTime(23, 0);
                        
                        // Kiểm tra xem còn đủ thời gian để tạo ít nhất 1 suất chiếu không
                        if ($earliestStart->gte($endOfDay)) {
                            $fail('Không thể tạo suất chiếu cho hôm nay vì không còn đủ thời gian (phải kết thúc trước 23:00).');
                        }
                    }
                },
            ],
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
            'max_showtimes' => 'nullable|in:auto,1,2,3,4,5',
        ], [
            'movie_id.required' => 'ID phim là bắt buộc.',
            'movie_id.exists' => 'Phim không tồn tại.',
            'date.required' => 'Ngày là bắt buộc.',
            'date.date' => 'Ngày không hợp lệ.',
            'room_ids.required' => 'Vui lòng chọn ít nhất một phòng chiếu.',
            'room_ids.array' => 'Danh sách phòng chiếu không hợp lệ.',
            'room_ids.min' => 'Vui lòng chọn ít nhất một phòng chiếu.',
            'room_ids.*.exists' => 'Một hoặc nhiều phòng chiếu không tồn tại.',
            'max_showtimes.in' => 'Số suất chiếu không hợp lệ.',
        ]);

        try {
            Log::info('Validation passed, proceeding with transaction for rooms: ' . implode(', ', $request->room_ids));

            $showtimesCreated = DB::transaction(function () use ($request) {
                $movie = Movie::findOrFail($request->movie_id);
                $timezone = 'Asia/Ho_Chi_Minh';
                $date = Carbon::parse($request->date, $timezone);
                $roomIds = $request->room_ids;
                $maxShowtimes = $request->max_showtimes ?? 'auto'; // Lấy giới hạn số suất
                $totalShowtimesCreated = 0;

                if (empty($movie->duration_minutes) || $movie->duration_minutes <= 0) {
                    throw new \Exception('Thời lượng phim không hợp lệ: ' . ($movie->duration_minutes ?? 'null') . ' phút.');
                }

                foreach ($roomIds as $roomId) {
                    $room = Room::with('roomType')->findOrFail($roomId);
                    if (!$room) {
                        throw new \Exception('Phòng chiếu ID ' . $roomId . ' không tồn tại.');
                    }

                    $now = Carbon::now($timezone);
                    $endOfDay = $date->copy()->setTime(23, 0);

                    // Xác định thời gian bắt đầu tạo suất chiếu
                    if ($date->isSameDay($now)) {
                        // Nếu tạo cho ngày hiện tại, bắt đầu từ thời điểm hiện tại
                        $earliestPossibleStart = $now->copy();
                        
                        // Làm tròn thời gian để hợp lý hơn (làm tròn lên 5 phút gần nhất)
                        $minutes = $earliestPossibleStart->minute;
                        $roundedMinutes = ceil($minutes / 5) * 5;
                        
                        if ($roundedMinutes >= 60) {
                            $earliestPossibleStart->addHour()->setMinute(0)->setSecond(0);
                        } else {
                            $earliestPossibleStart->setMinute($roundedMinutes)->setSecond(0);
                        }
                        
                        // Thêm 30 phút buffer
                        $earliestPossibleStart->addMinutes(30);
                        
                        $startOfDay = $earliestPossibleStart->copy();
                        
                        Log::info("Tạo suất chiếu cho ngày hôm nay. Bắt đầu từ {$startOfDay->format('H:i')} (thời gian hiện tại + 30 phút buffer).");
                    } else {
                        // Nếu tạo cho ngày khác, bắt đầu từ 8h sáng
                        $startOfDay = $date->copy()->setTime(8, 0);
                        
                        Log::info("Tạo suất chiếu cho ngày {$date->format('d/m/Y')}. Bắt đầu từ 8:00 sáng.");
                    }

                    $showtimesCreatedForRoom = 0;

                    Log::info("Bắt đầu tạo suất chiếu: Phim ID: {$movie->id} ({$movie->title}), Ngày: {$date->format('d/m/Y')}, Thời lượng phim: {$movie->duration_minutes} phút, Phòng ID: {$room->id} ({$room->name}), Khung giờ: {$startOfDay->format('H:i')} - {$endOfDay->format('H:i')}, Giới hạn: " . ($maxShowtimes === 'auto' ? 'Tự động' : $maxShowtimes . ' suất'));

                    // Lấy tất cả suất chiếu hiện có trong ngày cho phòng này
                    $existingShowtimes = Showtime::where('room_id', $room->id)
                        ->whereDate('start_time', $date->toDateString())
                        ->orderBy('start_time')
                        ->get();

                    // Lấy base_price từ room type một lần
                    $pricingService = new ShowtimePricingService();
                    $basePriceFromRoomType = $pricingService->calculateBasePriceForRoom($room);

                    // Tạo danh sách các khoảng thời gian bận
                    $busyIntervals = [];
                    foreach ($existingShowtimes as $existing) {
                        $busyIntervals[] = [
                            'start' => Carbon::parse($existing->start_time),
                            'end' => Carbon::parse($existing->end_time)->addMinutes(30) // thêm 30 phút buffer
                        ];
                    }

                    Log::info("Danh sách suất chiếu hiện có cho phòng {$room->name}:", [
                        'count' => $existingShowtimes->count(),
                        'showtimes' => $existingShowtimes->map(function($s) {
                            return [
                                'id' => $s->id,
                                'movie' => $s->movie->title ?? 'Unknown',
                                'start' => Carbon::parse($s->start_time)->format('H:i'),
                                'end' => Carbon::parse($s->end_time)->format('H:i')
                            ];
                        })->toArray()
                    ]);

                    Log::info("Khoảng thời gian bận (có buffer 30 phút):", [
                        'busy_intervals' => array_map(function($interval) {
                            return [
                                'start' => $interval['start']->format('H:i'),
                                'end' => $interval['end']->format('H:i')
                            ];
                        }, $busyIntervals)
                    ]);

                    // Tìm slot trống từ startOfDay đến endOfDay
                    $currentTime = $startOfDay->copy();
                    $movieDurationWithBuffer = $movie->duration_minutes + 30; // thêm 30 phút buffer giữa các suất

                    Log::info("Bắt đầu tìm slot trống:", [
                        'start_from' => $currentTime->format('H:i'),
                        'end_at' => $endOfDay->format('H:i'),
                        'movie_duration' => $movie->duration_minutes,
                        'movie_title' => $movie->title
                    ]);

                    while ($currentTime->copy()->addMinutes($movie->duration_minutes)->lte($endOfDay)) {
                        Log::info("While loop iteration:", [
                            'current_time' => $currentTime->format('H:i'),
                            'proposed_end_time' => $currentTime->copy()->addMinutes($movie->duration_minutes)->format('H:i'),
                            'end_of_day' => $endOfDay->format('H:i'),
                            'condition_met' => $currentTime->copy()->addMinutes($movie->duration_minutes)->lte($endOfDay) ? 'YES' : 'NO'
                        ]);

                        // Kiểm tra giới hạn số suất
                        if ($maxShowtimes !== 'auto' && $showtimesCreatedForRoom >= (int)$maxShowtimes) {
                            Log::info("Đã đạt giới hạn {$maxShowtimes} suất cho phòng {$room->name}");
                            break;
                        }

                        $proposedStart = $currentTime->copy();
                        $proposedEnd = $proposedStart->copy()->addMinutes($movie->duration_minutes);

                        Log::info("Kiểm tra slot:", [
                            'proposed_start' => $proposedStart->format('H:i'),
                            'proposed_end' => $proposedEnd->format('H:i'),
                            'room' => $room->name
                        ]);

                        // Kiểm tra xem thời gian đề xuất có trùng với khoảng thời gian bận không
                        $hasConflict = false;
                        $conflictDetails = [];
                        foreach ($busyIntervals as $index => $busy) {
                            // Kiểm tra overlap
                            if ($proposedStart->lt($busy['end']) && $proposedEnd->gt($busy['start'])) {
                                $hasConflict = true;
                                $conflictDetails[] = [
                                    'busy_start' => $busy['start']->format('H:i'),
                                    'busy_end' => $busy['end']->format('H:i'),
                                    'overlap_type' => 'conflict'
                                ];
                                // Nhảy đến sau khoảng thời gian bận và làm tròn
                                $nextTime = $busy['end']->copy();
                                
                                // Làm tròn thời gian để hợp lý hơn (chỉ làm tròn lên 5 phút gần nhất)
                                $minutes = $nextTime->minute;
                                $roundedMinutes = ceil($minutes / 5) * 5;
                                
                                if ($roundedMinutes >= 60) {
                                    $currentTime = $nextTime->copy()->addHour()->setMinute(0)->setSecond(0);
                                } else {
                                    $currentTime = $nextTime->copy()->setMinute($roundedMinutes)->setSecond(0);
                                }
                                
                                Log::info("Conflict detected! Jumping to: " . $currentTime->format('H:i'));
                                break;
                            }
                        }

                        if ($hasConflict) {
                            Log::warning("Slot bị conflict:", [
                                'proposed' => $proposedStart->format('H:i') . ' - ' . $proposedEnd->format('H:i'),
                                'conflicts' => $conflictDetails,
                                'jump_to' => $currentTime->format('H:i')
                            ]);
                            continue;
                        }

                        Log::info("Slot trống được tìm thấy! Tạo suất chiếu...");

                        if (!$hasConflict) {
                            // Tạo suất chiếu
                            Showtime::create([
                                'movie_id' => $movie->id,
                                'room_id' => $room->id,
                                'start_time' => $proposedStart,
                                'end_time' => $proposedEnd,
                                'base_price' => $basePriceFromRoomType,
                                'status' => 'scheduled',
                                'created_at' => now($timezone),
                                'updated_at' => now($timezone),
                            ]);
                            $showtimesCreatedForRoom++;

                            Log::info("Tạo suất chiếu thành công: {$proposedStart->format('H:i')} - {$proposedEnd->format('H:i')} cho phòng {$room->name}");

                            // Thêm suất chiếu mới vào danh sách bận để tránh trùng lặp
                            $busyIntervals[] = [
                                'start' => $proposedStart->copy(),
                                'end' => $proposedEnd->copy()->addMinutes(30) // buffer 30 phút
                            ];
                            
                            // Sắp xếp lại danh sách theo thời gian
                            usort($busyIntervals, function($a, $b) {
                                return $a['start']->timestamp - $b['start']->timestamp;
                            });

                            // Di chuyển đến thời gian tiếp theo và làm tròn
                            $nextTime = $proposedEnd->copy()->addMinutes(30);
                            
                            // Làm tròn thời gian bắt đầu tiếp theo để hợp lý hơn (chỉ làm tròn lên 5 phút gần nhất)
                            $minutes = $nextTime->minute;
                            $roundedMinutes = ceil($minutes / 5) * 5;
                            
                            if ($roundedMinutes >= 60) {
                                $currentTime = $nextTime->copy()->addHour()->setMinute(0)->setSecond(0);
                            } else {
                                $currentTime = $nextTime->copy()->setMinute($roundedMinutes)->setSecond(0);
                            }
                            
                            Log::info("Di chuyển đến thời gian tiếp theo: " . $currentTime->format('H:i'));
                        }
                    }

                    Log::info("Thoát khỏi while loop:", [
                        'final_current_time' => $currentTime->format('H:i'),
                        'final_proposed_end' => $currentTime->copy()->addMinutes($movie->duration_minutes)->format('H:i'),
                        'end_of_day' => $endOfDay->format('H:i'),
                        'showtimes_created_for_room' => $showtimesCreatedForRoom,
                        'max_showtimes' => $maxShowtimes,
                        'reason' => $currentTime->copy()->addMinutes($movie->duration_minutes)->gt($endOfDay) ? 'Not enough time' : 'Max showtimes reached'
                    ]);

                    $totalShowtimesCreated += $showtimesCreatedForRoom;
                    Log::info("Hoàn thành tạo suất chiếu cho phòng {$room->name}: {$showtimesCreatedForRoom} suất chiếu được tạo" . ($maxShowtimes !== 'auto' ? " (giới hạn: {$maxShowtimes})" : " (tự động)"));
                    
                    if ($showtimesCreatedForRoom === 0) {
                        Log::warning("Không tạo được suất chiếu nào cho phòng {$room->name}. Lý do có thể:", [
                            'start_time' => $startOfDay->format('H:i'),
                            'end_time' => $endOfDay->format('H:i'),
                            'existing_showtimes_count' => $existingShowtimes->count(),
                            'movie_duration' => $movie->duration_minutes,
                            'max_showtimes_setting' => $maxShowtimes
                        ]);
                    }
                }

                if ($totalShowtimesCreated === 0) {
                    Log::error("KHÔNG TẠO ĐƯỢC SUẤT CHIẾU NÀO!", [
                        'total_rooms' => count($roomIds),
                        'movie_id' => $movie->id,
                        'movie_title' => $movie->title,
                        'movie_duration' => $movie->duration_minutes,
                        'date' => $date->format('d/m/Y'),
                        'max_showtimes' => $maxShowtimes,
                        'timezone' => $timezone
                    ]);
                    throw new \Exception('Không thể tạo suất chiếu nào. Có thể do tất cả khung giờ đã có suất chiếu hoặc thời gian không phù hợp.');
                }

                return $totalShowtimesCreated;
            });

            return redirect()->route('admin.movies.show', $request->movie_id)
                ->with('success', "Tạo thành công $showtimesCreated suất chiếu cho ngày {$request->date}!");
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo suất chiếu tự động: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo suất chiếu: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái suất chiếu theo thời gian thực
     */
    public function updateStatuses()
    {
        try {
            $service = new ShowtimeStatusService();
            $updatedCount = $service->updateShowtimeStatuses();

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật trạng thái cho {$updatedCount} suất chiếu",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật trạng thái suất chiếu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái suất chiếu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái cho một suất chiếu cụ thể
     */
    public function updateSingleStatus($id)
    {
        try {
            $showtime = Showtime::findOrFail($id);
            $service = new ShowtimeStatusService();
            $updated = $service->updateSingleShowtimeStatus($showtime);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật trạng thái suất chiếu',
                    'new_status' => $showtime->fresh()->status->value
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không cần cập nhật trạng thái',
                    'current_status' => $showtime->status->value
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật trạng thái suất chiếu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái suất chiếu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái thủ công cho suất chiếu
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $showtime = Showtime::with('tickets.booking')->findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:scheduled,ongoing,completed,cancelled,postponed'
            ]);

            $oldStatus = $showtime->status->value;
            $newStatus = $request->status;

            // Kiểm tra logic nghiệp vụ dựa trên trạng thái mới
            switch ($newStatus) {
                case 'scheduled':
                    // Chỉ có thể đặt lại thành scheduled nếu suất chiếu chưa bắt đầu
                    if ($showtime->start_time->isPast()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không thể đặt trạng thái "scheduled" cho suất chiếu đã qua thời gian bắt đầu.'
                        ], 400);
                    }
                    break;

                case 'cancelled':
                    // Kiểm tra xem có vé đã được đặt không
                    $confirmedTickets = $showtime->tickets()->whereHas('booking', function($query) {
                        $query->where('status', 'confirmed');
                    })->count();
                    
                    if ($confirmedTickets > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => "Không thể hủy suất chiếu vì đã có {$confirmedTickets} vé được đặt và xác nhận. Vui lòng liên hệ khách hàng để xử lý hoàn tiền trước khi hủy.",
                            'tickets_count' => $confirmedTickets
                        ], 400);
                    }
                    break;

                case 'postponed':
                    // Chỉ có thể hoãn nếu suất chiếu chưa bắt đầu
                    if ($showtime->start_time->isPast()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không thể hoãn suất chiếu đã bắt đầu.'
                        ], 400);
                    }
                    
                    // Thông báo về số vé đã đặt (nếu có)
                    $confirmedTickets = $showtime->tickets()->whereHas('booking', function($query) {
                        $query->where('status', 'confirmed');
                    })->count();
                    
                    if ($confirmedTickets > 0) {
                        // Cần thông báo cho khách hàng về việc hoãn
                        Log::info("Suất chiếu ID: {$id} bị hoãn, có {$confirmedTickets} vé đã được đặt cần thông báo khách hàng.");
                    }
                    break;

                case 'ongoing':
                    if ($showtime->start_time->isFuture() || $showtime->end_time->isPast()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Trạng thái "ongoing" chỉ áp dụng khi suất chiếu đang diễn ra.'
                        ], 400);
                    }
                    break;

                case 'completed':
                    if (!$showtime->end_time->isPast()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Chỉ có thể đánh dấu hoàn thành khi suất chiếu đã kết thúc.'
                        ], 400);
                    }
                    break;
            }

            // Cập nhật trạng thái
            $showtime->status = $newStatus;
            $showtime->save();

            // Tạo thông báo tùy theo trạng thái
            $message = $this->getStatusUpdateMessage($oldStatus, $newStatus, $showtime);

            Log::info("Cập nhật trạng thái suất chiếu ID: {$id} từ '{$oldStatus}' thành '{$newStatus}'");

            return response()->json([
                'success' => true,
                'message' => $message,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'showtime_info' => [
                    'movie' => $showtime->movie->name ?? 'N/A',
                    'room' => $showtime->room->name ?? 'N/A',
                    'start_time' => $showtime->start_time->format('d/m/Y H:i'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật trạng thái suất chiếu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái suất chiếu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo thông báo phù hợp cho việc cập nhật trạng thái
     */
    private function getStatusUpdateMessage($oldStatus, $newStatus, $showtime)
    {
        $movieName = $showtime->movie->name ?? 'N/A';
        $startTime = $showtime->start_time->format('d/m/Y H:i');
        
        switch ($newStatus) {
            case 'cancelled':
                return "Đã hủy suất chiếu '{$movieName}' lúc {$startTime}. Hệ thống sẽ tự động xử lý hoàn tiền nếu có vé đã đặt.";
                
            case 'postponed':
                $confirmedTickets = $showtime->tickets()->whereHas('booking', function($query) {
                    $query->where('status', 'confirmed');
                })->count();
                
                $ticketNotice = $confirmedTickets > 0 ? " (Có {$confirmedTickets} vé đã đặt cần thông báo khách hàng)" : '';
                return "Đã hoãn suất chiếu '{$movieName}' lúc {$startTime}{$ticketNotice}.";
                
            case 'scheduled':
                return "Đã kích hoạt lại suất chiếu '{$movieName}' lúc {$startTime}.";
                
            default:
                return "Đã cập nhật trạng thái suất chiếu '{$movieName}' từ '{$oldStatus}' thành '{$newStatus}'.";
        }
    }
}