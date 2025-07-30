<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cinema;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class CinemaController extends Controller
{
    // ✅ Hiển thị danh sách quốc gia
    public function index(Request $request)
    {
        $query = Cinema::with('city')->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $cinemas = $query->paginate(10);

        // Giữ lại keyword khi chuyển trang
        $cinemas->appends($request->only('keyword'));

        return view('admin.cinemas.list', compact('cinemas'));
    }

    public function show($id)
{
    $cinema = Cinema::withTrashed()
        ->with(['city' => function ($query) {
            $query->withTrashed(); 
        }])
        ->findOrFail($id);

    return view('admin.cinemas.detail', compact('cinema'));
}

    // ✅ Hiển thị form thêm mới
    public function create()
    {
        $cities = City::orderBy('created_at', 'asc')->get()->reverse();
        return view('admin.cinemas.add', compact('cities'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'city_id'       => 'required|integer|exists:cities,id',
            'hotline'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'map_url'       => 'nullable|url|max:500',
            'opening_hours' => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:active,inactive',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000', // max 2MB
        ]);

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Đường dẫn lưu file trong public/assets/images/cinema
            $destinationPath = public_path('assets/images/cinema');

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            // Gán đường dẫn ảnh tương ứng để hiển thị asset
            $validated['image_url'] = 'images/cinema/' . $imageName;
        } else {
            $validated['image_url'] = null;
        }

        $cinema = Cinema::create($validated);

        // Tạo thông báo khi thêm mới rạp
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Cinema::class,
            'entity_id' => $cinema->id,
            'title' => 'Thêm mới rạp chiếu phim',
            'message' => 'Rạp "' . $cinema->name . '" đã được thêm mới.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'old_status' => null,
            'new_status' => json_encode($cinema->getAttributes()),
            'event_details' => json_encode(['action' => 'create', 'cinema_id' => $cinema->id]),
        ]);

        return redirect()->route('admin.cinemas.index')->with('success', 'Đã thêm rạp chiếu phim mới thành công.');
    }

    public function edit($id)
    {
        $cities = City::orderBy('created_at', 'asc')->get()->reverse();
        $cinema = Cinema::withTrashed()->findOrFail($id);
        return view('admin.cinemas.edit', compact('cinema', 'cities'));
    }

    // Cập nhật rạp
    public function update(Request $request, $id)
    {
        $cinema = Cinema::withTrashed()->findOrFail($id);
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'city_id'       => 'required|integer|exists:cities,id',
            'hotline'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'map_url'       => 'nullable|url|max:500',
            'opening_hours' => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:active,inactive',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($cinema->image_url && file_exists(public_path('assets/' . $cinema->image_url))) {
                unlink(public_path('assets/' . $cinema->image_url));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/images/cinema');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $validated['image_url'] = 'images/cinema/' . $imageName;
        } else {
            // Giữ nguyên ảnh cũ nếu không upload ảnh mới
            $validated['image_url'] = $cinema->image_url;
        }

        $oldData = $cinema->getOriginal();
        $cinema->update($validated);

        // Tạo thông báo khi cập nhật rạp
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Cinema::class,
            'entity_id' => $cinema->id,
            'title' => 'Cập nhật rạp chiếu phim',
            'message' => 'Rạp "' . $cinema->name . '" đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'old_status' => json_encode($oldData),
            'new_status' => json_encode($cinema->getAttributes()),
            'event_details' => json_encode(['action' => 'update', 'cinema_id' => $cinema->id]),
        ]);

        return redirect()->route('admin.cinemas.index')->with('success', 'Đã cập nhật rạp chiếu phim thành công.');
    }

    public function destroy(Cinema $cinema)
    {
        // Xóa mềm, không xóa ảnh luôn
        $oldData = $cinema->getOriginal();
        $cinema->delete();

        // Tạo thông báo khi xóa mềm rạp
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Cinema::class,
            'entity_id' => $cinema->id,
            'title' => 'Xóa rạp chiếu phim',
            'message' => 'Rạp "' . $cinema->name . '" đã bị xóa (tạm thời).',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'old_status' => json_encode($oldData),
            'new_status' => null,
            'event_details' => json_encode(['action' => 'delete', 'cinema_id' => $cinema->id]),
        ]);

        return redirect()->route('admin.cinemas.index')->with('success', 'Đã chuyển rạp vào thùng rác.');
    }

    public function trash(Request $request)
{
    $keyword = $request->keyword;

    $trashedCinemas = Cinema::onlyTrashed()
        ->when($keyword, fn($query) => $query->where('name', 'like', "%$keyword%"))
        ->with('city')
        ->orderByDesc('deleted_at') // lấy từ dưới lên (mới xóa lên trước)
        ->paginate(10);

    return view('admin.cinemas.trash', compact('trashedCinemas'));
}


    // Khôi phục
    public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->findOrFail($id);
        $cinema->restore();

        // Tạo thông báo khi khôi phục rạp
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Cinema::class,
            'entity_id' => $cinema->id,
            'title' => 'Khôi phục rạp chiếu phim',
            'message' => 'Rạp "' . $cinema->name . '" đã được khôi phục.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'old_status' => null,
            'new_status' => json_encode($cinema->getAttributes()),
            'event_details' => json_encode(['action' => 'restore', 'cinema_id' => $cinema->id]),
        ]);

        return redirect()->route('admin.cinemas.trash')->with('success', 'Đã khôi phục rạp.');
    }

    // Xóa vĩnh viễn
    public function forceDelete($id)
    {
        $cinema = Cinema::onlyTrashed()->findOrFail($id);
        $oldData = $cinema->getOriginal();

        // Xóa ảnh nếu có
        if ($cinema->image_url && file_exists(public_path('assets/' . $cinema->image_url))) {
            unlink(public_path('assets/' . $cinema->image_url));
        }

        $cinema->forceDelete();

        // Tạo thông báo khi xóa vĩnh viễn rạp
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Cinema::class,
            'entity_id' => $cinema->id,
            'title' => 'Xóa vĩnh viễn rạp chiếu phim',
            'message' => 'Rạp "' . $cinema->name . '" đã bị xóa vĩnh viễn.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'old_status' => json_encode($oldData),
            'new_status' => null,
            'event_details' => json_encode(['action' => 'force_delete', 'cinema_id' => $cinema->id]),
        ]);

        return redirect()->route('admin.cinemas.trash')->with('success', 'Đã xóa vĩnh viễn rạp.');
    }
}