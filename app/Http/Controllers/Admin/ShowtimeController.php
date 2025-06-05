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

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
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
                'in:scheduled,ongoing,completed,cancelled',
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
            $movieDuration = $movie->duration; 
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
            'date' => 'required|date|after_or_equal:today',
        ], [
            'movie_id.required' => 'ID phim là bắt buộc.',
            'movie_id.exists' => 'Phim không tồn tại.',
            'date.required' => 'Ngày là bắt buộc.',
            'date.date' => 'Ngày không hợp lệ.',
            'date.after_or_equal' => 'Ngày phải từ hôm nay trở đi.',
        ]);

        try {
            $showtimesCreated = DB::transaction(function () use ($request) {
                $movie = Movie::findOrFail($request->movie_id);
                $date = Carbon::parse($request->date)->setTimezone('Asia/Ho_Chi_Minh');
                
                // Lấy phòng đầu tiên (có thể mở rộng để chọn nhiều phòng)
                $room = Room::first();
                if (!$room) {
                    throw new \Exception('Không có phòng chiếu nào.');
                }

                // Thời gian hoạt động rạp
                $startOfDay = $date->copy()->setTime(8, 0); // 8:00 sáng
                $endOfDay = $date->copy()->setTime(22, 0); // 22:00 tối

                // Thời lượng phim (phút) + 30 phút khoảng cách
                $totalDuration = $movie->duration_minutes + 30;

                // Tính toán các suất chiếu
                $currentTime = $startOfDay->copy();
                $showtimesCreated = 0;

                while ($currentTime->copy()->addMinutes($movie->duration_minutes)->lte($endOfDay)) {
                    $startTime = $currentTime->copy();
                    $endTime = $currentTime->copy()->addMinutes($movie->duration_minutes);

                    // Kiểm tra trùng lặp suất chiếu trong cùng phòng và khung giờ
                    $existingShowtime = Showtime::where('room_id', $room->id)
                        ->where(function ($query) use ($startTime, $endTime) {
                            $query->whereBetween('start_time', [$startTime, $endTime])
                                  ->orWhereBetween('end_time', [$startTime, $endTime])
                                  ->orWhere(function ($q) use ($startTime, $endTime) {
                                      $q->where('start_time', '<=', $startTime)
                                        ->where('end_time', '>=', $endTime);
                                  });
                        })
                        ->exists();

                    if (!$existingShowtime) {
                        Showtime::create([
                            'movie_id' => $movie->id,
                            'room_id' => $room->id,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'base_price' => 100000, // Giá vé mặc định
                            'status' => 'scheduled',
                            'created_at' => now('Asia/Ho_Chi_Minh'),
                            'updated_at' => now('Asia/Ho_Chi_Minh'),
                        ]);
                        $showtimesCreated++;
                    }

                    // Tăng thời gian cho suất tiếp theo
                    $currentTime->addMinutes($totalDuration);
                }

                if ($showtimesCreated === 0) {
                    throw new \Exception('Không thể tạo suất chiếu do trùng lịch hoặc thời gian không phù hợp.');
                }

                return $showtimesCreated; // Trả về số lượng suất chiếu được tạo
            });

            return redirect()->back()
                ->with('success', "Tạo thành công $showtimesCreated suất chiếu cho ngày {$request->date}!");
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo suất chiếu tự động: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo suất chiếu: ' . $e->getMessage());
        }
    }
}