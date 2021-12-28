@extends('layouts.app_plain')
@section('title','login')
@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center " style="height:100vh;">
        <div class="col-md-6">
            <div class="text-center mb-3">
                <img src="{{asset('image/logo.png')}}" alt="office" style="width:80px">
            </div>
            <div class="card" style="height: 45vh">
                <div class="card-body">
                    <h5 class="text-center">Login</h5>
                    <h5 class="text-center text-muted small">Please fill the form</h5>
                    <form method="GET" action="{{ route('login_option') }}">
                        <div class="md-form">
                            <input type="phone" class="form-control text-center @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone" placeholder="Enter phone number" autofocus>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-theme btn-block" style="margin-top: 96px">
                            Continue
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
