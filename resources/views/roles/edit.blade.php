@extends('layouts.app')
@section('title','Role Edit')
@section('content')
<div class="card ">
    <div class="card-body">
        <form action="{{route('roles.update',$role->id)}}" method="POST" id="update">
            @csrf
            @method('PATCH')
            <div class="md-form">
                <label for="">Role</label>
                <input type="text" class="form-control" value="{{$role->name}}" name="name">
            </div>
            <div class="row">
                @foreach ($permissions as $permission)
                <div class="col-md-3 col-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="permissions[]" class="custom-control-input"
                            id="{{$permission->id}}" value="{{$permission->name}}"
                            @if(in_array($permission->id,$old_permissions)) checked @endif >
                        <label class="custom-control-label" for="{{$permission->id}}">{{ $permission->name }}</label>
                    </div>
                </div>
                @endforeach
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
{!! JsValidator::formRequest('App\Http\Requests\UpdateRole','#update') !!}

<script>

</script>
@endsection