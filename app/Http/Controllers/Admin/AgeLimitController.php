<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgeLimit;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

class AgeLimitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view age limit')->only('index');
        $this->middleware('can:create age limit')->only(['create', 'store']);
        $this->middleware('can:edit age limit')->only(['edit', 'update']);
        $this->middleware('can:delete age limit')->only('destroy');
    }
    public function index()
    {
        $ageLimits = AgeLimit::orderBy('min_age')->paginate(10);
        return view('admin.ageLimit.index', compact('ageLimits'));
    }

    public function create()
    {
        return view('admin.ageLimit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:age_limits,name',
            'description' => 'nullable|string',
            'min_age' => 'nullable|integer|min:0',
        ]);
        $ageLimit = AgeLimit::create($request->only('name', 'description', 'min_age'));

        // Tạo thông báo khi thêm mới giới hạn độ tuổi
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => AgeLimit::class,
            'entity_id' => $ageLimit->id,
            'title' => 'Thêm mới giới hạn độ tuổi',
            'message' => 'Giới hạn độ tuổi "' . $ageLimit->name . '" đã được thêm mới.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => json_encode($ageLimit->getAttributes()),
            'event_details' => json_encode(['action' => 'create', 'age_limit_id' => $ageLimit->id]),
        ]);

        return redirect()->route('admin.age_limits.index')->with('success', 'Thêm giới hạn độ tuổi thành công!');
    }

    public function edit($id)
    {
        $ageLimit = AgeLimit::findOrFail($id);
        return view('admin.ageLimit.edit', compact('ageLimit'));
    }

    public function update(Request $request, $id)
    {
        $ageLimit = AgeLimit::findOrFail($id);
        $oldData = $ageLimit->getOriginal();
        $request->validate([
            'name' => 'required|string|max:50|unique:age_limits,name,' . $id,
            'description' => 'nullable|string',
            'min_age' => 'nullable|integer|min:0',
        ]);
        $ageLimit->update($request->only('name', 'description', 'min_age'));

        // Tạo thông báo khi cập nhật giới hạn độ tuổi
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => AgeLimit::class,
            'entity_id' => $ageLimit->id,
            'title' => 'Cập nhật giới hạn độ tuổi',
            'message' => 'Giới hạn độ tuổi "' . $ageLimit->name . '" đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => json_encode($ageLimit->getAttributes()),
            'event_details' => json_encode(['action' => 'update', 'age_limit_id' => $ageLimit->id]),
        ]);

        return redirect()->route('admin.age_limits.index')->with('success', 'Cập nhật thành công!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        $ageLimits = AgeLimit::whereIn('id', $ids)->get();
        foreach ($ageLimits as $ageLimit) {
            $oldData = $ageLimit->getOriginal();
            $ageLimit->delete();
            // Tạo thông báo khi xóa từng giới hạn độ tuổi
            Notification::create([
                'user_id' => Auth::id(),
                'entity_type' => AgeLimit::class,
                'entity_id' => $ageLimit->id,
                'title' => 'Xóa giới hạn độ tuổi',
                'message' => 'Giới hạn độ tuổi "' . $ageLimit->name . '" đã bị xóa.',
                'type' => NotificationType::System,
                'priority' => 'low',
                'old_status' => json_encode($oldData),
                'new_status' => null,
                'event_details' => json_encode(['action' => 'delete', 'age_limit_id' => $ageLimit->id]),
            ]);
        }
        return redirect()->route('admin.age_limits.index')->with('success', 'Đã xóa các giới hạn độ tuổi đã chọn!');
    }

    public function destroy($id)
    {
        $ageLimit = AgeLimit::findOrFail($id);
        $oldData = $ageLimit->getOriginal();
        $ageLimit->delete();
        // Tạo thông báo khi xóa giới hạn độ tuổi
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => AgeLimit::class,
            'entity_id' => $ageLimit->id,
            'title' => 'Xóa giới hạn độ tuổi',
            'message' => 'Giới hạn độ tuổi "' . $ageLimit->name . '" đã bị xóa.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => json_encode($oldData),
            'new_status' => null,
            'event_details' => json_encode(['action' => 'delete', 'age_limit_id' => $ageLimit->id]),
        ]);
        return redirect()->route('admin.age_limits.index')->with('success', 'Đã xóa!');
    }
}