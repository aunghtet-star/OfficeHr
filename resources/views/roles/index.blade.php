@extends('layouts.app')
@section('title','Role')
@section('content')

@can('create_permission')
<a href="{{route('roles.create')}}" class="btn btn-theme btn-sm m-0 mb-3"><i class="fas fa-plus-square mr-1"></i> Create
    Role</a>
@endcan

<div class="card">
    <div class="card-body">
        <table class="table table-bordered datatable" style="width:100%">
            <thead>
                <th class="no-sort no-search"></th>
                <th>Role</th>
                <th>Permission</th>
                <th class="no-search">Update_at</th>
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
               
                ajax: '/roles/datatables/ssd',
                columns: [
                    { data: 'plus', name:'plus' , class: 'text-center'},
                    { data: 'name', name:'name' , class: 'text-center'},
                    { data: 'permission', name:'permission' , class: 'text-center'},
                    { data: 'updated_at', name: 'updated_at' , class : 'text-center'},
                    { data: 'action', name: 'action' , class : 'text-center'},
                ],
                order : [[ 3 ,"desc"]],
                
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
                        url: 'roles/'+ id,
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