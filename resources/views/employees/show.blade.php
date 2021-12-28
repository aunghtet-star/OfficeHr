@extends('layouts.app')
@section('title','Employee Detail')
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
                <div class="col-md-6 dashboard">
                 
                    <p class="text-muted ml-4"> <strong>Name</strong> - </i>{{$employee->name}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Email</strong> - </i>{{$employee->email}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Phone</strong> - </i>{{$employee->phone}}</p>
                    
                    <p class="text-muted ml-4"><strong>Nrc Number</strong> - </i>{{$employee->nrc_number}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Address</strong> - </i>{{$employee->address}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Gender</strong> - </i>{{ucfirst($employee->gender)}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Birthday</strong> - </i>{{$employee->birthday}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Start Income Day</strong> - </i>{{$employee->date_of_join}}</p>
                    
    
                    <p class="text-muted ml-4"><strong>Department</strong> - </i>
                        @if($employee->is_present == 1)
                        <button class="badge badge-pill badge-success">Live</button>
                        
                        @else
                        <button class="badge badge-pill badge-danger">Out</button>
                        
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection