<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    
    public function index(){
        $customers = User::query()->paginate(10);
        return view('admin.customers.list', compact('customers'));

    }

    public function show($id){
        $customer = User::findOrFail($id);
        return view('admin.customers.detail', compact('customer'));
    }
}
