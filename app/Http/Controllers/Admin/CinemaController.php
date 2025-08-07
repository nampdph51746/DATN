<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cinema;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            $query->withTrashed(); // Load cả thành phố đã bị xóa mềm
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

        Cinema::create($validated);

        return redirect()->route('admin.cinemas.index')->with('success', 'Đã thêm rạp chiếu phim mới thành công.');
    }

    public function edit(Cinema $cinema)
    {
        $cities = City::orderBy('created_at', 'asc')->get()->reverse();
        return view('admin.cinemas.edit', compact('cinema', 'cities'));
    }

    // Cập nhật rạp
    public function update(Request $request, Cinema $cinema)
    {
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

        $cinema->update($validated);

        return redirect()->route('admin.cinemas.index')->with('success', 'Đã cập nhật rạp chiếu phim thành công.');
    }

    public function destroy(Cinema $cinema)
    {
        // Xóa mềm, không xóa ảnh luôn
        $cinema->delete();

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

        return redirect()->route('admin.cinemas.trash')->with('success', 'Đã khôi phục rạp.');
    }

    // Xóa vĩnh viễn
    public function forceDelete($id)
    {
        $cinema = Cinema::onlyTrashed()->findOrFail($id);

        // Xóa ảnh nếu có
        if ($cinema->image_url && file_exists(public_path('assets/' . $cinema->image_url))) {
            unlink(public_path('assets/' . $cinema->image_url));
        }

        $cinema->forceDelete();

        return redirect()->route('admin.cinemas.trash')->with('success', 'Đã xóa vĩnh viễn rạp.');
    }
}