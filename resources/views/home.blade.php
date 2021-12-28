@extends('layouts.app')
@section('title','Home')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 profile_img">
                    <div class="text-center">
                        <img src="{{$employee->profile_image_path()}}" alt="">
                        <div class="profile-detail p-0 mt-2 mb-3">
                            <p class="mb-1">Employee ID</p>
                            <p class="text-muted mb-1">{{$employee->employee_id}}</p>
                            <p class="mb-1">Department</p>
                            <p class="text-muted mb-1 badge badge-pill badge-light border">{{$employee->department ? $employee->department->title : '-'}}</p>
                            @foreach ($employee->getRoleNames() as $role)
                            <p class="mb-0">
                                <span class="badge badge-pill badge-primary">
                                {{$role}}
                            </span>
                            </p>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection