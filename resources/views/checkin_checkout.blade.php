@extends('layouts.app_plain')
@section('title','Check In Check Out')
@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
        <div class="col-md-6 ">
            <div class="card ">
                <div class="card-body">
                    <div class="text-center">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate($hash_value)) !!} ">

                        <p class="text-muted mb-3">Scan me to check in</p>
                    </div>
                    <div class="text-center">
                        <input type="text" name="passcode" id="pincode-input1">
                        <p class="text-muted mb-3 mt-3">Enter the passcode</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        


        $(document).ready(function(){
            $('#pincode-input1').pincodeInput({inputs:6,complete:function(value, e, errorElement){
                console.log("code entered: " + value);
                
                $.ajax({
                    'url' : 'checkin_and_out',
                    'type' : 'POST',
                    'data' :  {
                        'checkin' : value
                    },
                    'success' : function(res){
                        if(res.status == 'success'){
                            Toast.fire({
                            icon: 'success',
                            title: res.msg
                        })
                        }
                        if(res.status == 'fail'){
                            Toast.fire({
                            icon: 'error',
                            title: res.msg
                        })
                        }
                        
                        $('.pincode-input-text-masked').val('');
                        $('.pincode-input-text').first().select().focus();
                    }
                })



                /*do some code checking here*/
                
                //$(errorElement).html("I'm sorry, but the code not correct");
        }});


        })
    </script>
@endsection