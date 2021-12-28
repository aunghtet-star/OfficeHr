@extends('layouts.app_plain')
@section('title','login_option')
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
                    
                        <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="pills-password-tab" data-toggle="pill" href="#pills-password" role="tab"
                                aria-controls="pills-password" aria-selected="true">Password</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="pills-biometric-tab" data-toggle="pill" href="#pills-biometric" role="tab"
                                aria-controls="pills-biometric" aria-selected="false">Biometric</a>
                            </li>
                            
                          </ul>
                          <div class="tab-content pt-2 pl-1" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
                                <form method="POST" action="{{route('login')}}">
                                @csrf
                                <div class="md-form">
                                    <input type="hidden" class="form-control text-center @error('phone') is-invalid @enderror" name="phone" value="{{ request()->phone }}" required autocomplete="phone" placeholder="Enter phone number" autofocus>

                                    <input type="password" class="form-control text-center @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password" placeholder="Enter Password" autofocus>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-theme btn-block">
                                    Continue
                                </button>
                            </form>
                            </div>
                            <div class="tab-pane fade" id="pills-biometric" role="tabpanel" aria-labelledby="pills-biometric-tab">
                                <input type="hidden" class="form-control text-center @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{ request()->phone }}" required autocomplete="phone" placeholder="Enter phone number" autofocus>
                                
                                <div class="text-center">
                                    <a href="" class="btn bio-login-btn ">
                                        <i class="fas fa-fingerprint"></i>
                                        <p class="text-center mb-0">finger</p>
                                    </a>
                                    <p class="text-center mb-0 mt-2">Login with FingerPrint</p>
                                </div>
                            </div>
                            
                          </div>
                        
                        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        const login = (event) => {
        event.preventDefault()
        new Larapass({
            login: 'webauthn/login',
            loginOptions: 'webauthn/login/options'
        }).login({
            phone: document.getElementById('phone').value
        }).then(function(res){
            window.location.replace('/');
        })
          .catch(error => console.log(error))
    }

    $('.bio-login-btn').on('click',function(event){
        login(event);
    })
})
    

    
</script>
@endsection