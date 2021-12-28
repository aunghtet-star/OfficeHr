<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $task = new Task();
        $task->project_id = $request->project_id;
        $task->status = $request->status;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->startdate = $request->startdate;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->save();
        
        $task->members()->sync($request->members);

        return 'success';
    }

    public function taskData(Request $request)
    {
        $project = Project::with('tasks')->where('id', $request->project_id)->firstOrFail();
        return view('components.task', compact('project'))->render();
    }

    public function update(Request $request, $id)
    {
        $task  = Task::findOrFail($id);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->startdate = $request->startdate;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->update();

        $task->members()->sync($request->members);

        return 'success';
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->members()->detach();
        $task->delete();

        return 'success';
    }

    public function taskDraggable(Request $request)
    {
        $project = Project::with('tasks')->where('id', $request->project_id)->firstOrFail();
        
        if ($request->pending_task) {
            $pending_tasks = explode(',', $request->pending_task);
            foreach ($pending_tasks as $key=>$task_id) {
                $task = collect($project->tasks)->where('id', $task_id)->first();
                if ($task) {
                    $task->sterialize_number = $key;
                    $task->status = 'pending';
                    $task->update();
                }
            }
        }

        if ($request->in_progress_task) {
            $in_progress_tasks = explode(',', $request->in_progress_task);
            foreach ($in_progress_tasks as $key=>$task_id) {
                $task = collect($project->tasks)->where('id', $task_id)->first();
                if ($task) {
                    $task->sterialize_number = $key;
                    $task->status = 'in_progress';
                    $task->update();
                }
            }
        }

        if ($request->complete_task) {
            $complete_tasks = explode(',', $request->complete_task);
            foreach ($complete_tasks as $key=>$task_id) {
                $task = collect($project->tasks)->where('id', $task_id)->first();
                if ($task) {
                    $task->sterialize_number = $key;
                    $task->status = 'complete';
                    $task->update();
                }
            }
        }

        return 'success';
    }
}
