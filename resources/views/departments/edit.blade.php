@extends('layouts.app')
@section('title','Department Edit')
@section('content')
    <div class="card ">
        <div class="card-body">
            <form action="{{route('departments.update',$department->id)}}" method="POST" id="update">
                @csrf
                @method('PATCH')
                <div class="md-form">
                    <label for="">Department</label>
                    <input type="text" class="form-control" value="{{$department->title}}" name="title">
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
{!! JsValidator::formRequest('App\Http\Requests\UpdateDepartment','#update') !!}
    
    <script>
        
        });
    </script>
@endsection