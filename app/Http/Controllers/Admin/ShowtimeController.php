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

            if ($showtime->tickets()->exists() && (
                $showtime->movie_id != $request->movie_id ||
                $showtime->room_id != $request->room_id ||
                $showtime->start_time != $request->start_time ||
                $showtime->end_time != $request->end_time
            )) {
                return redirect()->back()->with('error', 'Không thể sửa phim, phòng chiếu, hoặc thời gian vì đã có vé được đặt.');
            }

            $showtime->update([
                'movie_id' => $request->movie_id,
                'room_id' => $request->room_id,
                'start_time' => $start->toDateTimeString(),
                'end_time' => $end->toDateTimeString(),
                'base_price' => $request->base_price,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.showtimes.index')->with('success', 'Cập nhật suất chiếu thành công!');
        } catch (\Exception $e) {
            \Log::error('Lỗi khi cập nhật suất chiếu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật suất chiếu. Vui lòng thử lại.');
        }
    }

    public function storeAuto(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
            'date' => 'required|date',
            'max_showtimes' => 'required',
        ]);

        // Lấy dữ liệu
        $movieId = $request->movie_id;
        $roomIds = $request->room_ids;
        $date = $request->date;
        $maxShowtimes = $request->max_showtimes;

        // Tạo suất chiếu cho từng phòng
        foreach ($roomIds as $roomId) {
            // Tính toán số suất chiếu cần tạo
            $count = $maxShowtimes === 'auto' ? $this->getMaxShowtimes($movieId, $roomId, $date) : (int)$maxShowtimes;

            for ($i = 0; $i < $count; $i++) {
                // Tính toán thời gian bắt đầu/kết thúc cho từng suất chiếu (ví dụ: cách nhau 2 tiếng)
                $startTime = Carbon::parse($date)->addHours(9 + $i * 2); // bắt đầu từ 9h sáng
                $endTime = $startTime->copy()->addMinutes(\App\Models\Movie::find($movieId)->duration_minutes);

                // Kiểm tra trùng lịch ở đây nếu cần

                \App\Models\Showtime::create([
                    'movie_id' => $movieId,
                    'room_id' => $roomId,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'base_price' => 70000, // hoặc lấy từ phòng/phim
                    'status' => 'scheduled',
                ]);
            }
        }

        return redirect()->route('admin.movies.show', $movieId)->with('success', 'Đã tạo suất chiếu tự động!');
    }

    // Hàm tính số suất chiếu tối đa (ví dụ, bạn tự viết logic)
    function getMaxShowtimes($movieId, $roomId, $date) {
        // Ví dụ: trả về 5 suất chiếu tối đa
        return 5;
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
            $showtime = Showtime::findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:scheduled,ongoing,completed,cancelled,postponed'
            ]);

            // Kiểm tra logic nghiệp vụ
            if ($request->status === 'scheduled' && $showtime->start_time->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể đặt trạng thái "scheduled" cho suất chiếu đã qua.'
                ]);
            }

            // Kiểm tra xem có vé đã được đặt không
            if ($request->status === 'cancelled' && $showtime->tickets()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy suất chiếu đã có vé được đặt. Vui lòng liên hệ khách hàng để xử lý hoàn tiền.'
                ]);
            }

            $oldStatus = $showtime->status->value;
            $showtime->status = $request->status;
            $showtime->save();

            Log::info("Cập nhật trạng thái suất chiếu ID: {$id} từ '{$oldStatus}' thành '{$request->status}'");

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật trạng thái suất chiếu từ '{$oldStatus}' thành '{$request->status}'",
                'old_status' => $oldStatus,
                'new_status' => $request->status
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
}