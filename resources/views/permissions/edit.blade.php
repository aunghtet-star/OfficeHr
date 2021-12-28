@extends('layouts.app')
@section('title','Permission Edit')
@section('content')
    <div class="card ">
        <div class="card-body">
            <form action="{{route('permissions.update',$permission->id)}}" method="POST" id="update">
                @csrf
                @method('PATCH')
                <div class="md-form">
                    <label for="">Permission</label>
                    <input type="text" class="form-control" value="{{$permission->name}}" name="name">
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
{!! JsValidator::formRequest('App\Http\Requests\UpdatePermission','#update') !!}
    
    <script>
        
        });
    </script>
@endsection