@extends('layouts.app')
@section('title','Permission Create')
@section('content')
    <div class="card ">
        <div class="card-body">
            <form action="{{route('permissions.store')}}" method="POST" id="create">
                @csrf
                <div class="md-form">
                    <label for="">Permission</label>
                    <input type="text" class="form-control" name="name">
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
{!! JsValidator::formRequest('App\Http\Requests\StorePermission','#create'); !!}
    
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endsection