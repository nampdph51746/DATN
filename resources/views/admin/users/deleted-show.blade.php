@extends('layouts.admin.admin')

@section('content')

     <div class="container-xxl">
           <div class="row">
                 <!-- Profile Column -->
                 <div class="col-lg-4">
                       <div class="card overflow-hidden">
                              <div class="card-body" style="height: 470px">
                                    <div class="bg-primary profile-bg rounded-top p-5 position-relative mx-n3 mt-n3">
                                          <img src="{{ Storage::url($user->avatar_url) }}" alt=""
                                                class="avatar-lg border border-light border-3 rounded-circle position-absolute top-100 start-0 translate-middle ms-5">
                                    </div>
                                    <div class="mt-4 pt-3">
                                          <h4 class="mb-1"> {{ $user->name }}

                                             @if(!is_null($user->email_verified_at))
                                             <i class="bx bxs-badge-check text-success align-middle"></i>
                                             @endif
                                             
                                          </h4>
                                          <div class="mt-2">
                                                <p class="fs-15 mb-1 mt-1"><span class="text-dark fw-semibold">Email : </span>
                                                       {{ $user->email }}</p>
                                                <p class="fs-15 mb-0 mt-1"><span class="text-dark fw-semibold">Phone : </span> {{ $user->phone_number }}</p>
                                                <p class="fs-15 mb-0 mt-1"><span class="text-dark fw-semibold">Address : </span> {{ $user->address }}</p>
                                                <p class="fs-15 mb-0 mt-1"><span class="text-dark fw-semibold">Date of Birth : </span> {{ $user->date_of_birth }}</p>
                                          </div>
                                    </div>
                              </div>
                       </div>
                 </div>

                 <!-- Customer Details Column -->
                 <div class="col-lg-8">
                       <div class="card">
                              <div class="card-header d-flex align-items-center justify-content-between">
                                    <div>
                                          <h4 class="card-title">User Details</h4>
                                    </div>
                                    <div>

                                    </div>
                              </div>
                              <div class="card-body py-2">
                                    <div class="table-responsive">
                                          <table class="table mb-0">
                                                <tbody>
                                                   <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Status :</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->status }}</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">User ID :</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->id }}</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Role ID :</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->role->id }} ({{ $user->role->name }})</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Customer rank ID :</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->customerRank->id }} ({{ $user->customerRank->name }})</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Email verified at :</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->created_at }}</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Created at :</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->created_at }}</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Updated at</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->updated_at }}</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Last login</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->last_login_at }}</td>
                                                       </tr>
                                                       <tr>
                                                             <td class="px-0">
                                                                   <p class="fw-semibold text-dark mb-0">Disabled at</p>
                                                             </td>
                                                             <td class="text-dark fw-medium px-0">{{ $user->deleted_at }}</td>
                                                       </tr>
                                                </tbody>
                                          </table>
                                    </div>
                              </div>

                       </div>
                       <div class="text-end mt-3">
                              <a href="{{ route('users.deleted') }}" class="btn btn-primary"><i class="bx bx-arrow-back"></i> Quay lại</a>
                       </div>
                 </div> <!-- end col-lg-8 -->
           </div>
     </div>

@endsection