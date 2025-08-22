<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\alert;

class BannerController extends Controller
{
    // Hiển thị danh sách quốc gia chưa xóa
    public function index(Request $request)
{
    $query = Banner::orderBy('created_at', 'desc');

    if ($request->filled('keyword')) {
        $query->where('title', 'like', '%' . $request->keyword . '%');
    }

    $banners = $query->paginate(10);
    $banners->appends($request->only('keyword'));

    return view('admin.banners.list', compact('banners'));
}


    // Hiển thị danh sách quốc gia đã xóa mềm (trashed)
    public function trash(Request $request)
    {
        $query = Banner::onlyTrashed()->orderBy('deleted_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $banners = $query->paginate(10);
        $banners->appends($request->only('keyword'));

        return view('admin.countries.trash', compact('countries'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        return view('admin.banners.add');
    }

    // Lưu quốc gia mới
    public function store(Request $request)
{
    // Validate dữ liệu
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'required|image|max:5120', // 5MB
        'link' => 'nullable|url|max:255',
        'position' => 'required|integer|min:1',
        'status' => 'required|boolean',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ], [
        'title.required' => 'Tiêu đề không được để trống',
        'image.required' => 'Vui lòng chọn ảnh',
        'image.image' => 'Ảnh không hợp lệ',
        'image.max' => 'Ảnh không được vượt quá 5MB',
        'link.url' => 'Đường dẫn không hợp lệ',
        'position.required' => 'Thứ tự hiển thị không được để trống',
        'status.required' => 'Trạng thái bắt buộc chọn',
        'start_date.required' => 'Ngày bắt đầu không được để trống',
        'end_date.required' => 'Ngày kết thúc không được để trống',
        'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
    ]);

    // Upload ảnh
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('banners', 'public');
        $imageUrl = Storage::url($path);
    } else {
        return back()->withErrors(['image' => 'Vui lòng chọn ảnh'])->withInput();
    }

    // Đẩy các banner khác ra sau nếu trùng display_order và đang hiển thị
    Banner::where('display_order', '>=', $validated['position'])
        ->where('is_active', 1)
        ->whereDate('end_date', '>=', now())
        ->increment('display_order');

    // Tạo banner mới
    Banner::create([
        'title' => $validated['title'],
        'image_url' => $imageUrl,
        'link_url' => $validated['link'] ?? null,
        'display_order' => $validated['position'],
        'is_active' => $validated['status'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
    ]);

    return redirect()->route('admin.banners.index')
                     ->with('success', 'Đã thêm banner mới thành công.');
}


    // Hiển thị form sửa
    public function edit($id)
    {
        $Banner = Banner::findOrFail($id);
        return view('admin.countries.edit', compact('Banner'));
    }

    // Cập nhật quốc gia
    public function update(Request $request, $id)
    {
        $Banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code,' . $id,
        ]);

        $Banner->update($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Cập nhật thành công.');
    }

    // Xóa mềm quốc gia
    public function destroy($id)
    {
        $Banner = Banner::findOrFail($id);
        $Banner->delete();

        return redirect()->route('admin.countries.index')->with('success', 'Đã xóa quốc gia.');
    }

    // Xóa hàng loạt
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string'
        ]);

        $ids = explode(',', $request->ids);
        $banners = Banner::whereIn('id', $ids);
        
        $count = $banners->count();
        $banners->delete();

        return redirect()->route('admin.countries.index')
                        ->with('success', "Đã xóa {$count} quốc gia thành công.");
    }

    // Khôi phục quốc gia đã xóa mềm
    public function restore($id)
    {
        $Banner = Banner::onlyTrashed()->findOrFail($id);
        $Banner->restore();

        alert('Quốc gia đã được khôi phục thành công.');
        return redirect()->route('admin.countries.trash')->with('success', 'Quốc gia đã được khôi phục.');
    }

    // Xóa vĩnh viễn quốc gia
    public function forceDelete($id)
    {
        $Banner = Banner::onlyTrashed()->findOrFail($id);
        $Banner->forceDelete();

        alert('Quốc gia đã bị xóa vĩnh viễn.');
        return redirect()->route('admin.countries.trash')->with('success', 'Đã xóa quốc gia vĩnh viễn.');
    }
}