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
    $query = Banner::orderBy('display_order', 'asc'); // Sắp xếp theo thứ tự hiển thị

    if ($request->filled('keyword')) {
        $query->where('title', 'like', '%' . $request->keyword . '%');
    }

    $banners = $query->paginate(10);
    $banners->appends($request->only('keyword'));

    return view('admin.banners.list', compact('banners'));
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
    $banner = Banner::findOrFail($id);
    return view('admin.banners.edit', compact('banner'));
}

public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120', // 5MB
            'link_url' => 'nullable|url|max:255',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'image.image' => 'Ảnh không hợp lệ',
            'image.max' => 'Ảnh không được vượt quá 5MB',
            'link_url.url' => 'Đường dẫn không hợp lệ',
            'display_order.required' => 'Thứ tự hiển thị không được để trống',
            'is_active.required' => 'Trạng thái bắt buộc chọn',
            'start_date.required' => 'Ngày bắt đầu không được để trống',
            'end_date.required' => 'Ngày kết thúc không được để trống',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
        ]);

        // Upload ảnh mới nếu có, xóa ảnh cũ
        if ($request->hasFile('image')) {
            if ($banner->image_url) {
                $oldPath = str_replace('/storage/', '', $banner->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('banners', 'public');
            $banner->image_url = Storage::url($path);
        }

        // Đẩy các banner khác ra sau nếu trùng display_order
        if ($banner->display_order != $validated['display_order']) {
            Banner::where('display_order', '>=', $validated['display_order'])
                ->where('id', '!=', $banner->id)
                ->where('is_active', 1)
                ->where('end_date', '>=', now())
                ->increment('display_order');
        }

        $banner->update([
            'title' => $validated['title'],
            'link_url' => $validated['link_url'] ?? null,
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('admin.banners.index')
                         ->with('success', 'Cập nhật banner thành công.');
    }

    // Xóa mềm quốc gia
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('admin.banners.index')
                         ->with('success', 'Đã xóa banner (xóa mềm).');
    }

    // Danh sách banner đã xóa (trash)
    public function trash(Request $request)
    {
        $query = Banner::onlyTrashed()->orderBy('deleted_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $banners = $query->paginate(10);
        $banners->appends($request->only('keyword'));

        return view('admin.banners.trash', compact('banners'));
    }

    // Khôi phục banner đã xóa
    public function restore($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();

        return redirect()->route('admin.banners.trash')
                         ->with('success', 'Banner đã được khôi phục.');
    }

    // Xóa vĩnh viễn banner
    public function forceDelete($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);

        // Xóa file ảnh trước khi xóa bản ghi
        if ($banner->image_url) {
            $oldPath = str_replace('/storage/', '', $banner->image_url);
            \Storage::disk('public')->delete($oldPath);
        }

        $banner->forceDelete();

        return redirect()->route('admin.banners.trash')
                         ->with('success', 'Banner đã bị xóa vĩnh viễn.');
    }

    // Xóa hàng loạt banner
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string'
        ], [
            'ids.required' => 'Vui lòng chọn ít nhất một banner để xóa.'
        ]);

        $ids = explode(',', $request->ids);
        $banners = Banner::whereIn('id', $ids);
        $count = $banners->count();

        // Xóa file ảnh
        foreach ($banners->get() as $b) {
            if ($b->image_url) {
                $oldPath = str_replace('/storage/', '', $b->image_url);
                \Storage::disk('public')->delete($oldPath);
            }
        }

        $banners->delete();

        return redirect()->route('admin.banners.index')
                         ->with('success', "Đã xóa {$count} banner thành công.");
    }
}