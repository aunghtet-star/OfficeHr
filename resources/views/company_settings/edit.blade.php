@extends('layouts.app')
@section('title','Company Setting Edit')
@section('extra_css')
<style>
    .daterangepicker .drp-buttons .btn {
        margin-left: 1px !important;
        font-size: 9px !important;
        font-weight: bold;
        padding: 4px 8px;
    }

    .daterangepicker .drp-calendar.left {
        padding: 4px 0 4px 4px !important;
    }

    daterangepicker .drp-calendar {
        max-width: 186px !important;
    }

    .daterangepicker {
        width: 194px !important;
    }
</style>
@endsection
@section('content')
<div class="card ">
    <div class="card-body">
        <form action="{{route('company_settings.update',1)}}" method="POST" id="update">
            @csrf
            @method('PATCH')
            <div class="md-form">
                <label for="">Company Name</label>
                <input type="text" class="form-control" value="{{$company_setting->company_name}}" name="company_name">
            </div>

            <div class="md-form">
                <label for="">Company Email</label>
                <input type="text" class="form-control" value="{{$company_setting->company_email}}"
                    name="company_email">
            </div>

            <div class="md-form">
                <label for="">Company Phone</label>
                <input type="text" class="form-control" value="{{$company_setting->company_phone}}"
                    name="company_phone">
            </div>

            <div class="md-form">
                <label for="">Company Address</label>
                <textarea name="company_address"
                    class="md-textarea form-control pt-2">{{$company_setting->company_address}}</textarea>
            </div>

            <div class="md-form">
                <label for="">Office Start Time</label>
                <input type="text" class="form-control timepicker" value="{{$company_setting->office_start_time}}"
                    name="office_start_time">
            </div>

            <div class="md-form">
                <label for="">Office End Time</label>
                <input type="text" class="form-control timepicker" value="{{$company_setting->office_end_time}}"
                    name="office_end_time">
            </div>

            <div class="md-form">
                <label for="">Break Start Time</label>
                <input type="text" class="form-control timepicker" value="{{$company_setting->break_start_time}}"
                    name="break_start_time">
            </div>

            <div class="md-form">
                <label for="">Break End Time</label>
                <input type="text" class="form-control timepicker" value="{{$company_setting->break_end_time}}"
                    name="break_end_time">
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