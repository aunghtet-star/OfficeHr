@extends('layouts.app')
@section('title','Attendance Scan')
@section('content')
<div class="card mb-3">
  <div class="card-body">
    <div class="text-center">
      <img src="{{asset('image/qrscan.png')}}" width="200px" alt="">
    </div>
    <div class="text-center">
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-theme btn-sm" data-toggle="modal" data-target="#scanModal">
        Launch demo modal
      </button>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
      <div class="row mb-3">
          
          <div class="col-md-4">
              <select name="" class="form-control select-month">
                  <option value="">Please Choose</option>
                  <option value="01" @if(now()->format('m') == '01') selected @endif>Jan</option>
                  <option value="02" @if(now()->format('m') == '02') selected @endif>Feb</option>
                  <option value="03" @if(now()->format('m') == '03') selected @endif>Mar</option>
                  <option value="04" @if(now()->format('m') == '04') selected @endif>Apr</option>
                  <option value="05" @if(now()->format('m') == '05') selected @endif>May</option>
                  <option value="06" @if(now()->format('m') == '06') selected @endif>Jun</option>
                  <option value="07" @if(now()->format('m') == '07') selected @endif>Jul</option>
                  <option value="08" @if(now()->format('m') == '08') selected @endif>Aug</option>
                  <option value="09" @if(now()->format('m') == '09') selected @endif>Sep</option>
                  <option value="10" @if(now()->format('m') == '10') selected @endif>Oct</option>
                  <option value="11" @if(now()->format('m') == '11') selected @endif>Nov</option>
                  <option value="12" @if(now()->format('m') == '12') selected @endif>Dec</option>
              </select>
          </div>
          <div class="col-md-4">
              <select name="" class="form-control select-year">
                  <option value="">Please Choose</option>
                      @for($i=0;$i<5;$i++)
                          <option value="{{now()->subYears($i)->format('Y')}}" @if(now()->format('Y') == now()->subYears($i)->format('Y')) selected @endif>{{now()->subYears($i)->format('Y')}}</option>
                      @endfor
              </select>
          </div>
          <div class="col-md-4">
              <button class="btn btn-theme btn-block pt-2 pb-2 search"><i class="fas fa-search"></i> Search</button>
          </div>
      </div>
      <h5>Payroll</h5>
      <div class="payroll_table"></div>
      <h5>Attendance Overview</h5>
      <div class="attendance_overview_table"></div>
  </div>
</div>


<div class="card" >
  <div class="card-body">
    <h5>Attendance record</h5>
      <table class="table table-bordered datatable" style="width:100%" >
          <thead>
              <th class="no-sort no-search"></th>
              <th>Employee</th>
              <th>Date</th>
              <th>Check In</th>
              <th>Check Out</th>
          </thead>
      </table>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanModalLabel">QR Scan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <video id="video" width="100%" height="300px"></video>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('js/qr-scanner.umd.min.js')}}"></script>
<script>
  $(document).ready(function(){
            const videoElem = document.getElementById('video');
            const qrScanner = new QrScanner(videoElem, function(result){
                console.log(result);
                if(result){
                    $('#scanModal').modal('hide');
                    qrScanner.stop();

                    $.ajax({
                    'url' : 'attendance-scan/checkin_checkout_scan',
                    'type' : 'POST',
                    'data' :  {
                        'hash_value' : result
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
                    }
                })

                }
            });
            
            $('#scanModal').on('shown.bs.modal', function (e) {
                qrScanner.start();
            })

            $('#scanModal').on('hidden.bs.modal', function (e) {
                qrScanner.stop();
            })

            var table = $('.datatable').DataTable({
               
               ajax: '/my-attendances/datatables/ssd',
               columns: [
                   { data: 'plus', name:'plus' , class: 'text-center'},
                   { data: 'employee', name:'employee' , class: 'text-center'},
                   { data: 'date', name:'date' , class: 'text-center'},
                   { data: 'check_in_time', name:'check_in_time' , class: 'text-center'},
                   { data: 'check_out_time', name:'check_out_time' , class: 'text-center'},
               ],
               order : [[ 4 ,"desc"]],
               
           });
           
           MyPayrollTable();

            function MyPayrollTable(){

                var month = $('.select-month').val();
                var year = $('.select-year').val();

                $.ajax({
                    url:`/my-payroll/table?month=${month}&year=${year}`,
                    type: 'GET',
                    success: function(res){
                        $('.payroll_table').html(res);
                    }
                })
            }


          AttendanceOverviewTable();

          
          function AttendanceOverviewTable(){

          var month = $('.select-month').val();
          var year = $('.select-year').val();

            $.ajax({
                url:`/my-attendances/overview/table?month=${month}&year=${year}`,
                type: 'GET',
                success: function(res){
                    $('.attendance_overview_table').html(res);
                }
            });

            table.ajax.url(`/my-attendances/datatables/ssd?month=${month}&year=${year}`).load();
        }

            $('.select-month').select2({
                    placeholder : 'Choose Month',
                    theme: 'bootstrap4',
                });

            //Select 2
            $('.select-year').select2({
                placeholder : 'Choose Year',
                theme: 'bootstrap4',
            });

            $('.search').on('click',function(event){
                event.preventDefault();
                AttendanceOverviewTable();
                MyPayrollTable();
            })
    
           
    
        })
</script>
@endsection