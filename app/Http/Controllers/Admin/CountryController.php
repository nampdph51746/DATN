<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class CountryController extends Controller
{
    // Hiển thị danh sách quốc gia chưa xóa
    public function index(Request $request)
    {
        $query = Country::orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $countries = $query->paginate(10);
        $countries->appends($request->only('keyword'));

        return view('admin.countries.list', compact('countries'));
    }

    // Hiển thị danh sách quốc gia đã xóa mềm (trashed)
    public function trash(Request $request)
    {
        $query = Country::onlyTrashed()->orderBy('deleted_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $countries = $query->paginate(10);
        $countries->appends($request->only('keyword'));

        return view('admin.countries.trash', compact('countries'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        return view('admin.countries.add');
    }

    // Lưu quốc gia mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code',
        ]);

        $country = Country::create($validated);

        // Tạo thông báo khi thêm mới quốc gia
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Country::class,
            'entity_id' => $country->id,
            'title' => 'Thêm mới quốc gia',
            'message' => 'Quốc gia "' . $country->name . '" đã được thêm mới.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => json_encode($country->getAttributes()),
            'event_details' => json_encode(['action' => 'create', 'country_id' => $country->id]),
        ]);

        return redirect()->route('admin.countries.index')->with('success', 'Đã thêm quốc gia mới.');
    }

    // Hiển thị form sửa
    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    // Cập nhật quốc gia
    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code,' . $id,
        ]);

        $oldData = $country->getOriginal();
        $country->update($validated);

        // Tạo thông báo khi cập nhật quốc gia
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Country::class,
            'entity_id' => $country->id,
            'title' => 'Cập nhật quốc gia',
            'message' => 'Quốc gia "' . $country->name . '" đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => json_encode($country->getAttributes()),
            'event_details' => json_encode(['action' => 'update', 'country_id' => $country->id]),
        ]);

        return redirect()->route('admin.countries.index')->with('success', 'Cập nhật thành công.');
    }

    // Xóa mềm quốc gia
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $oldData = $country->getOriginal();
        $country->delete();

        // Tạo thông báo khi xóa mềm quốc gia
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Country::class,
            'entity_id' => $country->id,
            'title' => 'Xóa quốc gia',
            'message' => 'Quốc gia "' . $country->name . '" đã bị xóa (tạm thời).',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => null,
            'event_details' => json_encode(['action' => 'delete', 'country_id' => $country->id]),
        ]);

        alert('Quốc gia đã được xóa thành công.');
        return redirect()->route('admin.countries.index')->with('success', 'Đã xóa quốc gia.');
    }

    // Khôi phục quốc gia đã xóa mềm
    public function restore($id)
    {
        $country = Country::onlyTrashed()->findOrFail($id);
        $country->restore();

        // Tạo thông báo khi khôi phục quốc gia
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Country::class,
            'entity_id' => $country->id,
            'title' => 'Khôi phục quốc gia',
            'message' => 'Quốc gia "' . $country->name . '" đã được khôi phục.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => json_encode($country->getAttributes()),
            'event_details' => json_encode(['action' => 'restore', 'country_id' => $country->id]),
        ]);

        alert('Quốc gia đã được khôi phục thành công.');
        return redirect()->route('admin.countries.trash')->with('success', 'Quốc gia đã được khôi phục.');
    }

    // Xóa vĩnh viễn quốc gia
    public function forceDelete($id)
    {
        $country = Country::onlyTrashed()->findOrFail($id);
        $oldData = $country->getOriginal();
        $country->forceDelete();

        // Tạo thông báo khi xóa vĩnh viễn quốc gia
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Country::class,
            'entity_id' => $country->id,
            'title' => 'Xóa vĩnh viễn quốc gia',
            'message' => 'Quốc gia "' . $country->name . '" đã bị xóa vĩnh viễn.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => null,
            'event_details' => json_encode(['action' => 'force_delete', 'country_id' => $country->id]),
        ]);

        alert('Quốc gia đã bị xóa vĩnh viễn.');
        return redirect()->route('admin.countries.trash')->with('success', 'Đã xóa quốc gia vĩnh viễn.');
    }

    // Xóa nhiều quốc gia cùng lúc
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        
        if (empty($ids)) {
            return redirect()->route('admin.countries.index')
                ->with('error', 'Không có quốc gia nào được chọn để xóa.');
        }

        try {
            $countries = Country::whereIn('id', $ids)->get();
            
            if ($countries->isEmpty()) {
                return redirect()->route('admin.countries.index')
                    ->with('error', 'Không tìm thấy quốc gia nào để xóa.');
            }

            // Kiểm tra xem có quốc gia nào đang được sử dụng không
            $usedCountries = [];
            foreach ($countries as $country) {
                // Kiểm tra nếu có movies sử dụng country này
                if ($country->movies()->exists()) {
                    $usedCountries[] = $country->name;
                }
            }

            if (!empty($usedCountries)) {
                return redirect()->route('admin.countries.index')
                    ->with('error', 'Không thể xóa các quốc gia sau vì đang được sử dụng: ' . implode(', ', $usedCountries));
            }

            // Thực hiện xóa mềm
            Country::whereIn('id', $ids)->delete();

            return redirect()->route('admin.countries.index')
                ->with('success', 'Đã xóa ' . count($ids) . ' quốc gia thành công.');

        } catch (\Exception $e) {
            return redirect()->route('admin.countries.index')
                ->with('error', 'Có lỗi xảy ra khi xóa quốc gia: ' . $e->getMessage());
        }
    }

    // Khôi phục nhiều quốc gia cùng lúc
    public function bulkRestore(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        
        if (empty($ids)) {
            return redirect()->route('admin.countries.trash')
                ->with('error', 'Không có quốc gia nào được chọn để khôi phục.');
        }

        try {
            $restoredCount = Country::onlyTrashed()->whereIn('id', $ids)->restore();
            
            return redirect()->route('admin.countries.trash')
                ->with('success', 'Đã khôi phục ' . count($ids) . ' quốc gia thành công.');

        } catch (\Exception $e) {
            return redirect()->route('admin.countries.trash')
                ->with('error', 'Có lỗi xảy ra khi khôi phục quốc gia: ' . $e->getMessage());
        }
    }

    // Xóa vĩnh viễn nhiều quốc gia cùng lúc
    public function bulkForceDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        
        if (empty($ids)) {
            return redirect()->route('admin.countries.trash')
                ->with('error', 'Không có quốc gia nào được chọn để xóa vĩnh viễn.');
        }

        try {
            Country::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            
            return redirect()->route('admin.countries.trash')
                ->with('success', 'Đã xóa vĩnh viễn ' . count($ids) . ' quốc gia thành công.');

        } catch (\Exception $e) {
            return redirect()->route('admin.countries.trash')
                ->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn quốc gia: ' . $e->getMessage());
        }
    }

    // Hiển thị chi tiết quốc gia
    public function show($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.show', compact('country'));
    }
}