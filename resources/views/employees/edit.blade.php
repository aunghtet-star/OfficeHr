@extends('layouts.app')
@section('title','Employee Edit')
@section('content')
    <div class="card ">
        <div class="card-body">
            <form action="{{route('employees.update',$employee->id)}}" method="POST" id="update" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="md-form">
                    <label for="">Name</label>
                    <input type="text" class="form-control" value="{{$employee->name}}" name="name">
                </div>
                <div class="md-form">
                    <label for="">Employee Id</label>
                    <input type="number" class="form-control" value="{{$employee->employee_id}}" name="employee_id">
                </div>
                <div class="md-form">
                    <label for="">Phone</label>
                    <input type="number" class="form-control" value="{{$employee->phone}}" name="phone">
                </div>
                <div class="md-form">
                    <label for="">Email</label>
                    <input type="text" class="form-control" value="{{$employee->email}}" name="email">
                </div>
                <div class="form-group">
                    <label for="">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="male" @if($employee->gender == 'male') selected @endif>Male</option>
                        <option value="female" @if($employee->gender == 'female') selected @endif>Female</option>
                    </select>
                </div>
                <div class="md-form">
                    <label for="">Nrc_number</label>
                    <input type="text" class="form-control" value="{{$employee->nrc_number}}" name="nrc_number">
                </div>
                <div class="form-group">
                    <label for="">Department</label>
                    <select name="department_id" class="form-control">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{$department->id}}"
                                {{$employee->department_id == $department->id ? 'selected' : '_' }}
                                >{{$department->title}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="">Role And Grade</label>
                    <select name="roles[]" class="form-control select-role" multiple>
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{$role->name}}"  @if(in_array($role->name,$old_roles)) selected @endif >{{$role->name}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="md-form ">
                    <label for="">Birthday</label>
                    <input type="text" class="form-control datepicker" value="{{$employee->birthday}}" name="birthday">
                </div>
    
                <div class="md-form">
                    <label for="">Address</label>
                    <textarea name="address" class="md-textarea form-control">{{$employee->address}}</textarea>
                </div>
    
                <div class="md-form ">
                    <label for="">Date of join</label>
                    <input type="text" class="form-control date_of_join" value="{{$employee->date_of_join}}" name="date_of_join">
                </div>
    
                <div class="form-group">
                    <label for="">Is_present</label>
                    <select name="is_present" class="form-control">
                        <option value="1" @if($employee->is_present == 1) selected @endif>Yes</option>
                        <option value="0" @if($employee->is_present == 0) selected @endif>No</option>
                    </select>
                </div>
                <div class="form-group ">
                    <label for="">Profile Image</label>
                    <input type="file" class="form-control p-1" name="profile_img" id="profile_img" multiple>
                </div>
                <div class="preview_img">
                    <img src="{{$employee->profile_image_path()}}" alt="">
                </div>
                <div class="md-form">
                    <label for="">Passcode</label>
                    <input type="number" class="form-control"  name="passcode" value="{{$employee->passcode}}">
                </div>
                <div class="md-form">
                    <label for="">Password</label>
                    <input type="password" class="form-control"  name="password">
                </div>
                    <div class="d-flex justify-content-center">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-theme btn-block mt-4">Confirm</button>
                        </div>
                    </div>
                    
            </form>
        </div>
    </div>
@endsection

@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\UpdateEmployee','#update') !!}
    
    <script>
        $(document).ready(function(){
            $('.datepicker').daterangepicker({
                "startDate": moment(),
                "maxDate": moment(),
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : ('YYYY-MM-DD'),
                },
                "drops": "auto"
            }, function(start, end, label) {
            //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });

            $('.date_of_join').daterangepicker({
                "startDate": moment(),
                "maxDate": moment(),
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : ('YYYY-MM-DD'),
                },
                "drops": "auto"
            }, function(start, end, label) {
            //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });

            $('#profile_img').on('change',function(event){
                var file_length = document.getElementById('profile_img').files.length;
                $('.preview_img').html('');
                for(var i=0; file_length > i;i++){
                    $('.preview_img').append(`<img src=${URL.createObjectURL(event.target.files[i])} />`)
                    
                }
            })

        });
    </script>
@endsection