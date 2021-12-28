@extends('layouts.app')
@section('title','Employee')
@section('content')

@can('create_employee')
<a href="{{route('employees.create')}}" class="btn btn-theme btn-sm m-0 mb-3"><i class="fas fa-plus-square mr-1"></i> Create Employee</a>
@endcan

    <div class="card" >
        <div class="card-body">
            <table class="table table-bordered datatable" style="width:100%" >
                <thead>
                    <th class="no-sort no-search"></th>
                    <th class="no-sort"></th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th class="hidden no-search">Update_at</th>
                    <th class="no-search no-sort">Live/Out</th>
                    <th>Action</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            var table = $('.datatable').DataTable({
               
                ajax: '/employees/datatables/ssd',
                columns: [
                    { data: 'plus', name:'plus' , class: 'text-center'},
                    { data: 'profile_img', name: 'profile_img', class : 'text-center'},
                    { data: 'phone', name: 'phone', class : 'text-center' },
                    { data: 'email', name: 'email' , class : 'text-center'},
                    { data: 'department', name: 'department' , class : 'text-center'},
                    { data: 'role', name: 'role' , class : 'text-center'},
                    { data: 'updated_at', name: 'updated_at' , class : 'text-center'},
                    { data: 'is_present', name: 'is_present' , class : 'text-center'},
                    { data: 'action', name: 'action' , class : 'text-center'},
                ],
                order : [[ 6 ,"desc"]],
                
            });
            

            $(document).on('click','#delete',function(e){
                e.preventDefault();
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure to Delete?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yeah',
                    reverseButtons: true

                    }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $.ajax({
                        url: 'employees/'+ id,
                        type: "DELETE",
                       
                        success: function(){
                            table.ajax.reload();
                        }
                        })
                        
                    }
                })
                   
            })

            @if(session('create'))
                swal("Well done!", "{{session('create')}}", "success");
            @endif

            @if(session('update'))
                swal("Well done!", "{{session('update')}}", "success");
            @endif
        });
    </script>
@endsection