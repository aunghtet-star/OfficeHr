@extends('layouts.app')
@section('title','Attendance Create')
@section('content')
@include('layouts.error')
<div class="card ">
    <div class="card-body">
        <form action="{{route('attendances.store')}}" method="POST" id="create">
            @csrf
            <div class="form-group">
                <label for="">Employee</label>
                <select name="user_id" class="form-control select-role">
                    <option value="">Select Employee</option>
                    @foreach ($employees as $employee)
                    <option value="{{$employee->id ?? old('user_id')}}" @if($employee->id == old('user_id')) selected
                        @endif>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md-form">
                <label for="">Date</label>
                <input type="text" class="form-control date" name="date" value="{{old('date')}}">
            </div>

            <div class="md-form">
                <label for="">Check In Time</label>
                <input type="text" class="form-control timepicker" name="check_in_time"
                    value="{{old('check_in_time')}}">
            </div>

            <div class="md-form">
                <label for="">Check Out Time</label>
                <input type="text" class="form-control timepicker" name="check_out_time"
                    value="{{old('check_out_time')}}">
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
{!! JsValidator::formRequest('App\Http\Requests\StoreAttendance','#create'); !!}

<script>
    $(document).ready(function(){
            $('.date').daterangepicker({
                
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


            $('.timepicker').daterangepicker({
                
                "singleDatePicker": true,
                "timePicker" :true,
                "locale" : {
                    "format" : "HH:mm:ss"
                },
                "autoApply":true
            }, function(start, end, label) {
            //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });


            $('.timepicker').on('show.daterangepicker', function(ev, picker) {
                picker.container.find('.calendar-table').hide();
            });
        });
</script>
@endsection