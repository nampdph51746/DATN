<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

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

        $city = City::create($validated);

        // Tạo thông báo khi thêm mới thành phố
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => City::class,
            'entity_id' => $city->id,
            'title' => 'Thêm mới thành phố',
            'message' => 'Thành phố "' . $city->name . '" đã được thêm mới.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => json_encode($city->getAttributes()),
            'event_details' => json_encode(['action' => 'create', 'city_id' => $city->id]),
        ]);

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

        $oldData = $city->getOriginal();
        $city->update($validated);

        // Tạo thông báo khi cập nhật thành phố
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => City::class,
            'entity_id' => $city->id,
            'title' => 'Cập nhật thành phố',
            'message' => 'Thành phố "' . $city->name . '" đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => json_encode($city->getAttributes()),
            'event_details' => json_encode(['action' => 'update', 'city_id' => $city->id]),
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'Cập nhật thành công.');
    }

    // ✅ XÓA MỀM
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $oldData = $city->getOriginal();
        $city->delete();

        // Tạo thông báo khi xóa mềm thành phố
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => City::class,
            'entity_id' => $city->id,
            'title' => 'Xóa thành phố',
            'message' => 'Thành phố "' . $city->name . '" đã bị xóa (tạm thời).',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => null,
            'event_details' => json_encode(['action' => 'delete', 'city_id' => $city->id]),
        ]);

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

        // Tạo thông báo khi khôi phục thành phố
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => City::class,
            'entity_id' => $city->id,
            'title' => 'Khôi phục thành phố',
            'message' => 'Thành phố "' . $city->name . '" đã được khôi phục.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => json_encode($city->getAttributes()),
            'event_details' => json_encode(['action' => 'restore', 'city_id' => $city->id]),
        ]);

        return redirect()->route('admin.cities.trash')->with('success', 'Đã khôi phục thành phố.');
    }

    // ✅ XÓA VĨNH VIỄN
    public function forceDelete($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $oldData = $city->getOriginal();
        $city->forceDelete();

        // Tạo thông báo khi xóa vĩnh viễn thành phố
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => City::class,
            'entity_id' => $city->id,
            'title' => 'Xóa vĩnh viễn thành phố',
            'message' => 'Thành phố "' . $city->name . '" đã bị xóa vĩnh viễn.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => null,
            'event_details' => json_encode(['action' => 'force_delete', 'city_id' => $city->id]),
        ]);

        return redirect()->route('admin.cities.trash')->with('success', 'Đã xóa vĩnh viễn thành phố.');
    }
}