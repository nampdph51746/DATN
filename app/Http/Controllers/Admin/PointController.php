<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index(Request $request)
    {
        $query = Point::with('user');

        // Tìm kiếm theo tên hoặc email
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $points = $query->paginate(20);
        return view('admin.points.index', compact('points'));
    }

    public function show($id)
    {
        $point = Point::with('user', 'histories.booking')->findOrFail($id);
        return view('admin.points.show', compact('point'));
    }
}