@extends('layouts.app')
@section('title','Salary Create')
@section('content')
    <div class="card ">
        <div class="card-body">
            <form action="{{route('salaries.store')}}" method="POST" id="create">
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
                    <div class="form-group">
                        <label for="">Month</label>
                        <select name="month" class="form-control select-month">
                            <option value="">Please Choose</option>
                            <option value="01" >Jan</option>
                            <option value="02" >Feb</option>
                            <option value="03" >Mar</option>
                            <option value="04" >Apr</option>
                            <option value="05" >May</option>
                            <option value="06" >Jun</option>
                            <option value="07" >Jul</option>
                            <option value="08" >Aug</option>
                            <option value="09" >Sep</option>
                            <option value="10" >Oct</option>
                            <option value="11" >Nov</option>
                            <option value="12" >Dec</option>
                        </select>
                    </div>
                <div class="form-group">
                    <label for="">Year</label>
                    <select name="year" class="form-control select-year">
                        <option value="">Please Choose</option>
                            @for($i=0;$i<15;$i++)
                                <option value="{{now()->addYears(5)->subYears($i)->format('Y')}}" >{{now()->addYears(5)->subYears($i)->format('Y')}}</option>
                            @endfor
                    </select>
                </div>
                <div class="md-form">
                    <label for="">Salary</label>
                    <input type="text" class="form-control" name="amount">
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
{!! JsValidator::formRequest('App\Http\Requests\StoreSalary','#create'); !!}
    
    <script>
        $(document).ready(function(){
            $('.select-month').select2({
                    placeholder : 'Choose Month',
                    theme: 'bootstrap4',
                });

            //Select 2
            $('.select-year').select2({
                placeholder : 'Choose Year',
                theme: 'bootstrap4',
            });
        });
    </script>
@endsection