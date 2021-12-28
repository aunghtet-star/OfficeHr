@extends('layouts.app')
@section('title','Department Create')
@section('content')
    <div class="card ">
        <div class="card-body">
            <form action="{{route('departments.store')}}" method="POST" id="create">
                @csrf
                <div class="md-form">
                    <label for="">Department</label>
                    <input type="text" class="form-control" name="title">
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
{!! JsValidator::formRequest('App\Http\Requests\StoreDepartment','#create'); !!}
    
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endsection