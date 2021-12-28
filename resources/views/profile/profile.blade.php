@extends('layouts.app')
@section('title','Profile')
@section('content')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 profile_img">
                    <div class="text-center">
                        <img src="{{$employee->profile_image_path()}}" alt="">
                        <div class="profile-detail p-0 mt-2 mb-3">
                            <p class="mb-1">Employee ID</p>
                            <p class="text-muted mb-1">{{$employee->employee_id}}</p>
                            <p class="mb-1">Department</p>
                            <p class="text-muted mb-1 badge badge-pill badge-light border">{{$employee->department ? $employee->department->title : '-'}}</p>
                            @foreach ($employee->getRoleNames() as $role)
                            <p class="mb-0">
                                <span class="badge badge-pill badge-primary">
                                {{$role}}
                            </span>
                            </p>
                            @endforeach
                            
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6 dashboard">
                 
                    <p class="text-muted ml-4"> <strong>Name</strong> - </i>{{$employee->name}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Email</strong> - </i>{{$employee->email}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Phone</strong> - </i>{{$employee->phone}}</p>
                    
                    <p class="text-muted ml-4"><strong>Nrc Number</strong> - </i>{{$employee->nrc_number}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Address</strong> - </i>{{$employee->address}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Gender</strong> - </i>{{ucfirst($employee->gender)}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Birthday</strong> - </i>{{$employee->birthday}}</p>
                    
                    <p class="text-muted ml-4"> <strong>Start Income Day</strong> - </i>{{$employee->date_of_join}}</p>
                    
    
                    <p class="text-muted ml-4"><strong>Department</strong> - </i>
                        @if($employee->is_present == 1)
                        <button class="badge badge-pill badge-success">Live</button>
                        
                        @else
                        <button class="badge badge-pill badge-danger">Out</button>
                        
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body finger-print">
                <h5>Finger Print Add</h5>   
                <a href="" class="btn bio-register-btn bio-register-do">
                    <i class="fas fa-fingerprint"></i>
                    <p class="mb-0 mt-1"><i class="fas fa-plus-circle"></i></p>
                </a>

                
                <span class="biometric-loop"></span>
                
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <a href="" class="btn btn-theme btn-block logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        //biometric register
        const register = (event) => {
        event.preventDefault()
        new Larapass({
            register: 'webauthn/register',
            registerOptions: 'webauthn/register/options'
        }).register()
          .then(function(res){
            Toast.fire({
                icon: 'success',
                title: 'Finger-print added'
            })
          })
          .catch(function(res){
              console.log(res);
          })
    }

    $('.bio-register-do').on('click',function(event){
        register(event);
    })
    //document.getElementById('bio-register-form').addEventListener('submit', register);

        
   
        $(document).ready(function(){

            biometricData();

            //biometric
            function biometricData(){
                $.ajax({
                url : 'biometrics/options',
                type : 'GET',
            }).done(function(res){
                $('.biometric-loop').html(res);
            })

            }

            //biometric delete
            var id = $(this).data('id');
            $(document).on('click','.bio-delete-btn',function(){
                $.ajax({
                    'url' : `bio-finger-btn/destroy/${id}`,
                    'type' : 'DELETE',
                    'success' : function(res){
                        biometricData();
                    }
                })
            })

            //logout
            $('.logout').on('click',function(e){
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure to logout?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yeah',
                    reverseButtons: true

                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/logout',
                            type: "POST",
                            
                            success: function(){
                                window.location.reload();
                            }
                        })
                    }
                    })


                
            })
        })
    </script>
@endsection