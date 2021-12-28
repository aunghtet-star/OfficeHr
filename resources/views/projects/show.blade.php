@extends('layouts.app')
@section('title','My Project Detail')
@section('extra_css')
    <style>
        .alert-warning{
            background-color: #fff3cd99;
        }
        .alert-info{
            background-color: #d1ecf199;
        }
        .alert-success{
            background-color: #d4edda99;
        }
        
        .task-item{
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 8px;
            background : #fff;
        }
        .select2-container {
            z-index: 9999999;
        }

        .sortable-ghost{
            border: 2px dashed #000;
            background: #ddd;
        }

    </style>
@endsection
@section('content')
<div class="col-md-12 mb-3">
    <div class="row">
        <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">{{$project->title}}</h5>
                        <p class="mb-2">Start Date - {{$project->startdate}} , <span>Deadline - {{$project->deadline}}</span></p>
                        <p class="mb-3">Priority - <span class="
                            @if($project->priority == 'high') 
                            badge badge-pill badge-danger 
                            @elseif($project->priority == 'middle') 
                            badge badge-pill badge-info  
                            @elseif($project->priority == 'low') 
                            badge badge-pill badge-dark @endif">
                            {{$project->priority}}</span>
                            <span>Status - </span>
                            <span class="
                            @if($project->status == 'pending') 
                            badge badge-pill badge-warning 
                            @elseif($project->status == 'in_progress') 
                            badge badge-pill badge-info  
                            @elseif($project->status == 'complete') 
                            badge badge-pill badge-success @endif">
                            {{$project->status}}</span>
                        </p>
        
                        <div>
                            <h5>Description</h5>
                            <p>{{$project->description}}</p>
                        </div>
        
                        <div class="mb-3">
                            <h5>Leaders</h5>
                            @foreach($project->leaders ?? [] as $leader)
                            <img src="{{$leader->profile_image_path()}}" class="thumbnail2" alt="">
                            @endforeach
                        </div>
        
                        <div class="mb-3">
                            <h5>Members</h5>
                            @foreach($project->members ?? [] as $member)
                            <img src="{{$member->profile_image_path()}}" class="thumbnail2" alt="">
                            @endforeach
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Pictures</h5>
                    <div id="image">
                        @foreach($project->images ?? [] as $image)
                        <img src="{{asset('storage/project/'.$image)}}" class="thumbnail2" alt="">
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Files</h5>
                    @foreach($project->files ?? [] as $file)
                    <a href="{{asset('storage/project/'.$file)}}" class="pdf-thumbnail" target="_blank"><i class="fas fa-file-pdf"></i>File {{$loop->iteration}}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>       
</div>

<div class="task-data"></div>

@endsection

@section('scripts')
<!-- jsDelivr :: Sortable :: Latest (https://www.jsdelivr.com/package/npm/sortablejs) -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        $(document).ready(function(){
            var project_id = "{{$project->id}}";
            var leaders = @json($project->leaders);
            var members = @json($project->members);

            var member_task_options = '';

            leaders.forEach(function(leader){
                member_task_options +=  `<option value=${leader.id}>${leader.name}</option>`;
            })

            members.forEach(function(member){
                member_task_options +=  `<option value=${member.id}>${member.name}</option>`;
            })

            function sortable(){
                var pending_task = document.getElementById('pending-task');
                var in_progress_task = document.getElementById('in_progress-task');
                var complete_task = document.getElementById('complete-task');
                
                
                var sortable = Sortable.create(pending_task,{
                    group: "sorting-task", 
                    ghostClass: "sortable-ghost",  // Class name for the drop placeholder
                    draggable: ".task-item",  // Specifies which items inside the element should be draggable
                    animation: 200,  // ms, animation speed moving items when sorting, `0` — without animation
                    store:{
                        set : function(sortable){
                            var order = sortable.toArray();
                            localStorage.setItem('pending_task',order.join(','));
                        }
                    },
                    onSort: function (evt) {
                        setTimeout(() => {
                            var pending_task = localStorage.getItem('pending_task');
                            
                            console.log(pending_task);

                            $.ajax({
                                url : `/tasks-draggable?project_id=${project_id}&pending_task=${pending_task}`,
                                type : 'GET',
                                success : function(res){
                                    console.log(res);
                                }
                            })
                        }, 1000);
                    }
                });

                var sortable = Sortable.create(in_progress_task,{
                    group: "sorting-task", 
                    ghostClass: "sortable-ghost",  // Class name for the drop placeholder
                    draggable: ".task-item",  // Specifies which items inside the element should be draggable
                    animation: 200,  // ms, animation speed moving items when sorting, `0` — without animation
                    store:{
                        set : function(sortable){
                            var order = sortable.toArray();
                            localStorage.setItem('in_progress_task',order.join(','));
                        }
                    },
                    onSort: function (evt) {
                        setTimeout(() => {
                            var in_progress_task = localStorage.getItem('in_progress_task');
                            

                            $.ajax({
                                url : `/tasks-draggable?project_id=${project_id}&in_progress_task=${in_progress_task}`,
                                type : 'GET',
                                success : function(res){
                                }
                            })
                        }, 1000); 
                    }
                });

                var sortable = Sortable.create(complete_task,{
                    group: "sorting-task", 
                    ghostClass: "sortable-ghost",  // Class name for the drop placeholder
                    draggable: ".task-item",  // Specifies which items inside the element should be draggable
                    animation: 200,  // ms, animation speed moving items when sorting, `0` — without animation
                    store:{
                        set : function(sortable){
                            var order = sortable.toArray();
                            localStorage.setItem('complete_task',order.join(','));
                        }
                    },
                    onSort: function (evt) {
                        setTimeout(() => {
                            var complete_task = localStorage.getItem('complete_task');
                            
                            

                            $.ajax({
                                url : `/tasks-draggable?project_id=${project_id}&complete_task=${complete_task}`,
                                type : 'GET',
                                success : function(res){
                                    console.log(res);
                                }
                            })
                        }, 1000);
                    }
                });


            }

            function addTask(){
                $.ajax({
                    url : `/tasks-data?project_id=${project_id}`,
                    type : 'GET',
                    success : function(res){
                        $('.task-data').html(res);
                        sortable();
                    }
                })
            }

            addTask();

            new Viewer(document.getElementById('image'), {
            viewed() {
                viewer.zoomTo(1);
            },
            });


            $(document).on('click','#add-pending-task',function(e){
                e.preventDefault();
               

                Swal.fire({
                    title: 'Task Create',
                    html : `<form id="pending_task_form">
                    <input type="hidden" name="status" value="pending" />
                    <input type="hidden" name="project_id" value="${project_id}" />
                    <div class="md-form">
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title">
                    </div>

                    <div class="md-form">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control md-textarea" rows="5"></textarea>
                    </div>

                    <div class="md-form ">
                        <label for="">Start Date</label>
                        <input type="text" class="form-control date" name="startdate">
                    </div>

                    <div class="md-form ">
                        <label for="">Deadline</label>
                        <input type="text" class="form-control date" name="deadline">
                    </div>

                    <div class="form-group">
                        <label for="" class="text-left" style="width:100%">Task Member</label>
                        <select name="members[]" class="form-control select-role" multiple >
                            <option >Select </option>
                            ${member_task_options}
                        </select>
                    </div>

                    <div class="form-group" >
                        <label for="" class="text-left" style="width:100%">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="">Select Priority</option>
                                <option value="high">High</option>
                                <option value="middle">Middle</option>
                                <option value="low">Low</option>
                        </select>
                    </div>
                    </form>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $('#pending_task_form').serialize();
                        
                        $.ajax({
                            url : '/tasks',
                            type : 'POST',
                            data : form_data,
                            success : function(res){
                                addTask();
                            }
                        })
                    }
                })

                //Select 2
                $('.select-role').select2({
                  theme: 'bootstrap4',
                });

                $('.date').daterangepicker({
                "startDate": moment(),
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : ('YYYY-MM-DD'),
                },
                    "drops": "auto"
                    }, function(start, end, label) {
                    //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                    });
            })

            $(document).on('click','#add-in_progress-task',function(e){
                e.preventDefault();
               

                Swal.fire({
                    title: 'Task Create',
                    html : `<form id="in_progress_task_form">
                    <input type="hidden" name="status" value="in_progress" />
                    <input type="hidden" name="project_id" value="${project_id}" />
                    <div class="md-form">
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title">
                    </div>

                    <div class="md-form">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control md-textarea" rows="5"></textarea>
                    </div>

                    <div class="md-form ">
                        <label for="">Start Date</label>
                        <input type="text" class="form-control date" name="startdate">
                    </div>

                    <div class="md-form ">
                        <label for="">Deadline</label>
                        <input type="text" class="form-control date" name="deadline">
                    </div>

                    <div class="form-group">
                        <label for="" class="text-left" style="width:100%">Task Member</label>
                        <select name="members[]" class="form-control select-role" multiple >
                            <option >Select </option>
                            ${member_task_options}
                        </select>
                    </div>

                    <div class="form-group" >
                        <label for="" class="text-left" style="width:100%">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="">Select Priority</option>
                                <option value="high">High</option>
                                <option value="middle">Middle</option>
                                <option value="low">Low</option>
                        </select>
                    </div>
                    </form>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $('#in_progress_task_form').serialize();
                        
                        $.ajax({
                            url : '/tasks',
                            type : 'POST',
                            data : form_data,
                            success : function(res){
                                addTask();
                            }
                        })
                    }
                })

                //Select 2
                $('.select-role').select2({
                  theme: 'bootstrap4',
                });

                $('.date').daterangepicker({
                "startDate": moment(),
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : ('YYYY-MM-DD'),
                },
                    "drops": "auto"
                    }, function(start, end, label) {
                    //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                    });
            })

            $(document).on('click','#add-complete-task',function(e){
                e.preventDefault();
               

                Swal.fire({
                    title: 'Task Create',
                    html : `<form id="complete_task_form">
                    <input type="hidden" name="status" value="complete" />
                    <input type="hidden" name="project_id" value="${project_id}" />
                    <div class="md-form">
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title">
                    </div>

                    <div class="md-form">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control md-textarea" rows="5"></textarea>
                    </div>

                    <div class="md-form ">
                        <label for="">Start Date</label>
                        <input type="text" class="form-control date" name="startdate">
                    </div>

                    <div class="md-form ">
                        <label for="">Deadline</label>
                        <input type="text" class="form-control date" name="deadline">
                    </div>

                    <div class="form-group">
                        <label for="" class="text-left" style="width:100%">Task Member</label>
                        <select name="members[]" class="form-control select-role" multiple >
                            <option >Select </option>
                            ${member_task_options}
                        </select>
                    </div>

                    <div class="form-group" >
                        <label for="" class="text-left" style="width:100%">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="">Select Priority</option>
                                <option value="high">High</option>
                                <option value="middle">Middle</option>
                                <option value="low">Low</option>
                        </select>
                    </div>
                    </form>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $('#complete_task_form').serialize();
                        
                        $.ajax({
                            url : '/tasks',
                            type : 'POST',
                            data : form_data,
                            success : function(res){
                                addTask();
                            }
                        })
                    }
                })

                //Select 2
                $('.select-role').select2({
                  theme: 'bootstrap4',
                });

                $('.date').daterangepicker({
                "startDate": moment(),
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : ('YYYY-MM-DD'),
                },
                    "drops": "auto"
                    }, function(start, end, label) {
                    //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                    });
            })

            $(document).on('click','#edit-task',function(e){
                e.preventDefault();
                var task = $(this).data('task');
                    task =  JSON.parse(atob(task));
                
                var task_members = $(this).data('task-members');
                    task_members = JSON.parse(atob(task_members));

                    var member_task_options = '';

                leaders.forEach(function(leader){
                    member_task_options +=  `<option value=${leader.id} ${(task_members.includes(leader.id) ? 'selected' : '')}>${leader.name}</option>`;
                })

                members.forEach(function(member){
                    member_task_options +=  `<option value=${member.id} ${(task_members.includes(member.id) ? 'selected' : '')}>${member.name}</option>`;
                })
                    
                Swal.fire({
                    title: 'Task Create',
                    html : `<form id="edit_pending_task_form">
                    <div class="md-form">
                        <label for="" class="active">Title</label>
                        <input type="text" class="form-control " name="title" value="${task.title}">
                    </div>

                    <div class="md-form">
                        <label for="" class="active">Description</label>
                        <textarea name="description" class="form-control md-textarea" rows="5">${task.description}</textarea>
                    </div>

                    <div class="md-form ">
                        <label for="" class="active">Start Date</label>
                        <input type="text" class="form-control date" name="startdate" value="${task.startdate}">
                    </div>

                    <div class="md-form ">
                        <label for="" class="active">Deadline</label>
                        <input type="text" class="form-control date" name="deadline" value="${task.deadline}">
                    </div>

                    <div class="form-group">
                        <label for="" class="text-left" style="width:100%">Task Member</label>
                        <select name="members[]" class="form-control select-role" multiple >
                            <option >Select </option>
                            ${member_task_options}
                        </select>
                    </div>

                    <div class="form-group" >
                        <label for="" class="text-left" style="width:100%">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="">Select Priority</option>
                                <option value="high" ${(task.priority == 'high' ? 'selected' : '')} >High</option>
                                <option value="middle" ${(task.priority == 'middle' ? 'selected' : '')} >Middle</option>
                                <option value="low" ${(task.priority == 'low' ? 'selected' : '')} >Low</option>
                        </select>
                    </div>
                    </form>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $('#edit_pending_task_form').serialize();
                        
                        $.ajax({
                            url : `/tasks/${task.id}`,
                            type : 'PATCH',
                            data : form_data,
                            success : function(res){
                                addTask();
                            }
                        })
                    }
                })

                //Select 2
                $('.select-role').select2({
                  theme: 'bootstrap4',
                });

                $('.date').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : ('YYYY-MM-DD'),
                },
                    "drops": "auto"
                    }, function(start, end, label) {
                    //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                    });
            
            })

            $(document).on('click','#delete-task',function(e){
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
                        url: '/tasks/'+ id,
                        type: "DELETE",
                       
                        success: function(){
                            addTask();
                        }
                        })
                        
                    }
                })
                   
            })
                 
           
              
                
        })
    </script>
@endsection