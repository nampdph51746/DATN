<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeatType;
use Illuminate\Http\Request;

class AdminSeatTypeController extends Controller
{
    public function index()
    {
        $seatTypes = SeatType::paginate(10);
        return view('admin.SeatType.index', compact('seatTypes'));
        
    }

    public function create()
    {
        return view('admin.SeatType.create');
    }
    public function store(Request $request)
    {
        $request->validate([
    'name' => 'required|string|max:50|unique:seat_types,name',
    'price_modifier' => 'required|numeric',
    'color_code' => 'nullable|string|max:20',
    'description' => 'nullable|string|max:1000',
], [
    'name.required' => 'Tên loại ghế là bắt buộc.',
    'name.string' => 'Tên loại ghế phải là chuỗi ký tự.',
    'name.max' => 'Tên loại ghế không được vượt quá 50 ký tự.',
    'name.unique' => 'Tên loại ghế đã tồn tại, vui lòng chọn tên khác.',
    'price_modifier.required' => 'Hệ số giá là bắt buộc.',
    'price_modifier.numeric' => 'Hệ số giá phải là một số.',
    'color_code.string' => 'Mã màu phải là chuỗi ký tự.',
    'color_code.max' => 'Mã màu không được vượt quá 20 ký tự.'
]);

        SeatType::create([
            'name' => $request->name,
            'price_modifier' => $request->price_modifier,
            'color_code' => $request->color_code,
            'description' => $request->description,
        ]);

        return redirect()->route('seat-type.index')->with('success', 'Seat Type created successfully.');
    }

    public function edit($id)
    {
        $seatType = SeatType::findOrFail($id);
        return view('admin.SeatType.edit', compact('seatType'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
        'name' => 'required|string|max:50|unique:seat_types,name,' . $id,
        'price_modifier' => 'required|numeric',
        'color_code' => 'nullable|string|max:20',
        'description' => 'nullable|string|max:1000',
    ], [
        'name.required' => 'Tên loại ghế là bắt buộc.',
        'name.string' => 'Tên loại ghế phải là chuỗi ký tự.',
        'name.max' => 'Tên loại ghế không được vượt quá 50 ký tự.',
        'name.unique' => 'Tên loại ghế đã tồn tại, vui lòng chọn tên khác.',
        'price_modifier.required' => 'Hệ số giá là bắt buộc.',
        'price_modifier.numeric' => 'Hệ số giá phải là một số.',
        'color_code.string' => 'Mã màu phải là chuỗi ký tự.',
        'color_code.max' => 'Mã màu không được vượt quá 20 ký tự.',
        'description.string' => 'Mô tả phải là chuỗi ký tự.',
        'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
    ]);

        $seatType = SeatType::findOrFail($id);
        $seatType->update([
            'name' => $request->name,
            'price_modifier' => $request->price_modifier,
            'color_code' => $request->color_code,
            'description' => $request->description,
        ]);

        return redirect()->route('seat-type.index')->with('success', 'Seat Type updated successfully.');
    }

    public function destroy($id)
    {
        $seatType = SeatType::findOrFail($id);
        $seatType->delete();

        return redirect()->route('seat-type.index')->with('success', 'Seat Type deleted successfully.');
    }

    public function restore($id)
    {
        $seatType = SeatType::withTrashed()->findOrFail($id);
        $seatType->restore();

        return redirect()->route('seat-type.index')->with('success', 'Seat Type restored successfully.');
    }
    public function trash()
    {
        $seatTypes = SeatType::onlyTrashed()->paginate(10);
        return view('admin.SeatType.trash', compact('seatTypes'));
    }
    public function forceDelete($id)
    {
        $seatType = SeatType::onlyTrashed()->findOrFail($id);
        $seatType->forceDelete();

        return redirect()->route('seat-type.trash')->with('success', 'Loại ghế đã được xóa vĩnh viễn.');
    }
    
}
