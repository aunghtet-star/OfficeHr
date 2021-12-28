@section('extra_css')
    <style>
        
    </style>
@endsection
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5>Task</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-warning">Pending</div>
                        <div class="card-body alert-warning">
                            <div id="pending-task">
                                @foreach(collect($project->tasks)->sortBy('sterialize_number')->where('status','pending') as $task)
                                <div class="task-item mb-2" data-id="{{$task->id}}">
                                    <div class="d-flex justify-content-between ">
                                        <p class="mb-0">{{$task->title}}</p>
                                        <div class="action-task">
                                            <a href="" id="edit-task" data-task="{{base64_encode(json_encode($task))}}" data-task-members = "{{base64_encode(json_encode(collect($task->members)->pluck('id')->toArray()))}}"><i class="fas fa-edit text-warning"></i></a> 
                                            <a href="" id="delete-task" data-id= {{$task->id}}><i class="fas fa-trash text-danger "></i> </a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-0 text-small"><small><i class="fas fa-clock"></i> {{Carbon\Carbon::parse($task->startdate)->format('M d')}}</small></p>
                                            <span class="
                                                @if($task->priority == 'high') 
                                                badge badge-pill badge-danger 
                                                @elseif($task->priority == 'middle') 
                                                badge badge-pill badge-info  
                                                @elseif($task->priority == 'low') 
                                                badge badge-pill badge-dark @endif">
                                                {{$task->priority}}
                                            </span>
                                        </div>
                                        <p class="mb-0 align-items-center mt-2">
                                            @foreach($task->members as $member)
                                            <img src="{{$member->profile_image_path()}}" class="thumbnail2" alt="">
                                            @endforeach
                                        </p>   
                                    </div>
                                </div>
                            @endforeach
                            </div>

                            <div class="task-item text-center">
                                <a href="" id="add-pending-task"><i class="fas fa-plus-circle"></i> Add Task</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info">In Progress</div>
                        <div class="card-body alert-info">
                            <div id="in_progress-task">
                                @foreach(collect($project->tasks)->sortBy('sterialize_number')->where('status','in_progress') as $task)
                                <div class="task-item mb-2" data-id= {{$task->id}}>
                                    <div class="d-flex justify-content-between ">
                                        <p class="mb-0">{{$task->title}}</p>
                                        <div class="action-task">
                                            <a href="" id="edit-task" data-task="{{base64_encode(json_encode($task))}}" data-task-members = "{{base64_encode(json_encode(collect($task->members)->pluck('id')->toArray()))}}"><i class="fas fa-edit text-warning"></i></a> 
                                            <a href="" id="delete-task" data-id= {{$task->id}}><i class="fas fa-trash text-danger "></i> </a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-0 text-small"><small><i class="fas fa-clock"></i> {{Carbon\Carbon::parse($task->startdate)->format('M d')}}</small></p>
                                            <span class="
                                                @if($task->priority == 'high') 
                                                badge badge-pill badge-danger 
                                                @elseif($task->priority == 'middle') 
                                                badge badge-pill badge-info  
                                                @elseif($task->priority == 'low') 
                                                badge badge-pill badge-dark @endif">
                                                {{$task->priority}}
                                            </span>
                                        </div>
                                        <p class="mb-0 align-items-center mt-2">
                                            @foreach($task->members as $member)
                                            <img src="{{$member->profile_image_path()}}" class="thumbnail2" alt="">
                                            @endforeach
                                        </p>   
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="task-item text-center">
                                <a href="" id="add-in_progress-task"><i class="fas fa-plus-circle"></i> Add Task</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success">Complete</div>
                        <div class="card-body alert-success">
                            <div id="complete-task">
                                @foreach(collect($project->tasks)->sortBy('sterialize_number')->where('status','complete') as $task)
                                <div class="task-item mb-2" data-id= {{$task->id}}>
                                    <div class="d-flex justify-content-between ">
                                        <p class="mb-0">{{$task->title}}</p>
                                        <div class="action-task">
                                            <a href="" id="edit-task" data-task="{{base64_encode(json_encode($task))}}" data-task-members = "{{base64_encode(json_encode(collect($task->members)->pluck('id')->toArray()))}}"><i class="fas fa-edit text-warning"></i></a> 
                                            <a href="" id="delete-task" data-id= {{$task->id}}><i class="fas fa-trash text-danger "></i> </a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-0 text-small"><small><i class="fas fa-clock"></i> {{Carbon\Carbon::parse($task->startdate)->format('M d')}}</small></p>
                                            <span class="
                                                @if($task->priority == 'high') 
                                                badge badge-pill badge-danger 
                                                @elseif($task->priority == 'middle') 
                                                badge badge-pill badge-info  
                                                @elseif($task->priority == 'low') 
                                                badge badge-pill badge-dark @endif">
                                                {{$task->priority}}
                                            </span>
                                        </div>
                                        <p class="mb-0 align-items-center mt-2">
                                            @foreach($task->members as $member)
                                            <img src="{{$member->profile_image_path()}}" class="thumbnail2" alt="">
                                            @endforeach
                                        </p>   
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="task-item text-center">
                                <a href="" id="add-complete-task"><i class="fas fa-plus-circle"></i> Add Task</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>