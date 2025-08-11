<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('country')->latest();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $cities = $query->paginate(10);
        $cities->appends($request->only('keyword'));

        return view('admin.cities.list', compact('cities'));
    }

    public function create()
    {
        $countries = Country::latest()->get();
        return view('admin.cities.add', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);

        City::create($validated);

        return redirect()->route('admin.cities.index')->with('success', 'Đã thêm thành phố mới.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $countries = Country::latest()->get();

        return view('admin.cities.edit', compact('city', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);

        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', 'Cập nhật thành công.');
    }

    // ✅ XÓA MỀM
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('admin.cities.trash')->with('success', 'Đã xóa thành phố (tạm thời).');
    }

    // ✅ XEM DANH SÁCH ĐÃ XÓA
    public function trash(Request $request)
    {
        $query = City::onlyTrashed()->with('country')->latest();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $cities = $query->paginate(10);
        $cities->appends($request->only('keyword'));

        return view('admin.cities.trash', compact('cities'));
    }

    // ✅ KHÔI PHỤC
    public function restore($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->restore();

        return redirect()->route('admin.cities.trash')->with('success', 'Đã khôi phục thành phố.');
    }

    // ✅ XÓA VĨNH VIỄN
    public function forceDelete($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->forceDelete();

        return redirect()->route('admin.cities.trash')->with('success', 'Đã xóa vĩnh viễn thành phố.');
    }

    // Xóa nhiều thành phố cùng lúc
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        
        if (empty($ids)) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'Không có thành phố nào được chọn để xóa.');
        }

        try {
            $cities = City::whereIn('id', $ids)->get();
            
            if ($cities->isEmpty()) {
                return redirect()->route('admin.cities.index')
                    ->with('error', 'Không tìm thấy thành phố nào để xóa.');
            }

            // Kiểm tra xem có thành phố nào đang được sử dụng không
            $usedCities = [];
            foreach ($cities as $city) {
                // Kiểm tra nếu có cinemas sử dụng city này
                if ($city->cinemas()->exists()) {
                    $usedCities[] = $city->name;
                }
            }

            if (!empty($usedCities)) {
                return redirect()->route('admin.cities.index')
                    ->with('error', 'Không thể xóa các thành phố sau vì đang được sử dụng: ' . implode(', ', $usedCities));
            }

            // Thực hiện xóa mềm
            City::whereIn('id', $ids)->delete();

            return redirect()->route('admin.cities.index')
                ->with('success', 'Đã xóa ' . count($ids) . ' thành phố thành công.');

        } catch (\Exception $e) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'Có lỗi xảy ra khi xóa thành phố: ' . $e->getMessage());
        }
    }

    // Khôi phục nhiều thành phố cùng lúc
    public function bulkRestore(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        
        if (empty($ids)) {
            return redirect()->route('admin.cities.trash')
                ->with('error', 'Không có thành phố nào được chọn để khôi phục.');
        }

        try {
            $restoredCount = City::onlyTrashed()->whereIn('id', $ids)->restore();
            
            return redirect()->route('admin.cities.trash')
                ->with('success', 'Đã khôi phục ' . count($ids) . ' thành phố thành công.');

        } catch (\Exception $e) {
            return redirect()->route('admin.cities.trash')
                ->with('error', 'Có lỗi xảy ra khi khôi phục thành phố: ' . $e->getMessage());
        }
    }

    // Xóa vĩnh viễn nhiều thành phố cùng lúc
    public function bulkForceDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        
        if (empty($ids)) {
            return redirect()->route('admin.cities.trash')
                ->with('error', 'Không có thành phố nào được chọn để xóa vĩnh viễn.');
        }

        try {
            City::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            
            return redirect()->route('admin.cities.trash')
                ->with('success', 'Đã xóa vĩnh viễn ' . count($ids) . ' thành phố thành công.');

        } catch (\Exception $e) {
            return redirect()->route('admin.cities.trash')
                ->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn thành phố: ' . $e->getMessage());
        }
    }
}