@extends('layouts.app')
@section('title','Project Edit')
@section('content')
    <div class="card ">
        @include('layouts.error')
        <div class="card-body">
            <form action="{{route('projects.update',$project->id)}}" method="POST" id="update" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="md-form">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" value="{{$project->title}}">
                </div>

                <div class="md-form">
                    <label for="">Description</label>
                    <textarea name="description" class="form-control md-textarea" rows="5">{{$project->description}}</textarea>
                </div>
                
                <div class="form-group ">
                    <label for="">Image(Only Image)</label>
                    <input type="file" class="form-control p-1" name="images[]" id="images" accept="images/.png,.jpeg,.jpg" multiple>
                </div>

                <div class="preview_img">
                    @if($project->images)
                    @foreach($project->images as $image)
                        <img src="{{asset('storage/project/'.$image)}}" alt="">
                    @endforeach
                    @endif
                </div>

                <div class="form-group ">
                    <label for="">File(Only Pdf)</label>
                    <input type="file" class="form-control p-1" name="files[]" id="files" accept="application/pdf" multiple>
                </div>
                <div class="preview_img">
                    @if($project->files)
                    @foreach($project->files as $file)
                    <a href="{{asset('storage/project/'.$file)}}" class="pdf-thumbnail" target="_blank"><i class="fas fa-file-pdf"></i>File {{$loop->iteration}}</a>
                    @endforeach
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Project Leader</label>
                    <select name="leaders[]" class="form-control select-role" multiple>
                        <option value="">Select Leader</option>
                            @foreach($employees as $employee)
                            <option value="{{$employee->id}}" @if(in_array($employee->id,collect($project->leaders)->pluck('id')->toArray())) selected @endif>{{$employee->name}}</option>
                            @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Project Member</label>
                    <select name="members[]" class="form-control select-role" multiple>
                        <option value="">Select Member</option>
                            @foreach($employees as $employee)
                            <option value="{{$employee->id}}" @if(in_array($employee->id,collect($project->members)->pluck('id')->toArray())) selected @endif>{{$employee->name}}</option>
                            @endforeach
                    </select>
                </div>

                <div class="md-form ">
                    <label for="">Start Date</label>
                    <input type="text" class="form-control date" name="startdate" value="{{$project->startdate}}">
                </div>

                <div class="md-form ">
                    <label for="">Deadline</label>
                    <input type="text" class="form-control date" name="deadline" value="{{$project->deadline}}">
                </div>

                <div class="form-group">
                    <label for="">Priority</label>
                    <select name="priority" class="form-control select-role">
                        <option value="">Select Priority</option>
                            <option value="high" @if($project->priority == 'high') selected @endif>High</option>
                            <option value="middle" @if($project->priority == 'middle') selected @endif>Middle</option>
                            <option value="low" @if($project->priority == 'low') selected @endif>Low</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" class="form-control select-role">
                        <option value="">Select Status</option>
                            <option value="pending" @if($project->status == 'pending') selected @endif>Pending</option>
                            <option value="in_progress" @if($project->status == 'in_progress') selected @endif>In Progress</option>
                            <option value="complete" @if($project->status == 'complete') selected @endif>Complete</option>
                    </select>
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
{!! JsValidator::formRequest('App\Http\Requests\UpdateProject','#update') !!}
    
    <script>
        $(document).ready(function(){
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


            $('#images').on('change',function(event){
                var file_length = document.getElementById('images').files.length;
                $('.preview_img').html('');
                for(var i=0; file_length > i;i++){
                    $('.preview_img').append(`<img src=${URL.createObjectURL(event.target.files[i])} />`)
                    
                }
            })
        });
    </script>
@endsection