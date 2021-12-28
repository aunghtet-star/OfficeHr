<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use Carbon\Carbon;
use App\Project_leader;
use App\Project_member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreProject;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployee;
use App\Http\Requests\UpdateProject;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateEmployee;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('view_project')) {
            return abort(404);
        }
        return view('projects.index');
    }


    public function ssd()
    {
        if (!auth()->user()->can('view_project')) {
            return abort(404);
        }
        $projects = Project::with('leaders');
        return Datatables::of($projects)
        ->addColumn('plus', function ($each) {
            return null;
        })
        ->editColumn('description', function ($each) {
            return Str::limit($each->description, 50);
        })
        ->addColumn('leaders', function ($each) {
            $output = '';
            foreach ($each->leaders ?? [] as $leader) {
                $output .= '<img src="'.$leader->profile_image_path().'" class="thumbnail2 m-2">';
            }
            return $output;
        })
        ->addColumn('members', function ($each) {
            $output = '';
            foreach ($each->members ?? [] as $member) {
                $output .= '<img src="'.$member->profile_image_path().'" class="thumbnail2 m-2">';
            }
            return $output;
        })
        ->addColumn('priority', function ($each) {
            if ($each->priority == 'high') {
                return '<span class="badge badge-pill badge-danger">'.$each->priority.'</span>';
            } elseif ($each->priority == 'middle') {
                return '<span class="badge badge-pill badge-info">'.$each->priority.'</span>';
            } elseif ($each->priority == 'low') {
                return '<span class="badge badge-pill badge-dark">'.$each->priority.'</span>';
            }
        })
        ->addColumn('status', function ($each) {
            if ($each->status == 'pending') {
                return '<span class="badge badge-pill badge-warning">'.$each->status.'</span>';
            } elseif ($each->status == 'in_progress') {
                return '<span class="badge badge-pill badge-info">'.$each->status.'</span>';
            } elseif ($each->status == 'complete') {
                return '<span class="badge badge-pill badge-success">'.$each->status.'</span>';
            }
        })
        ->editColumn('updated_at', function ($each) {
            return Carbon::parse($each->updated_at)->format('d-m-Y H:i:s A');
        })
        ->addColumn('action', function ($each) {
            $show ='';
            $edit ='';
            $delete ='';
            if (auth()->user()->can('view_project')) {
                $show = '<a href="'.route('projects.show', $each->id).'"><i class="fas fa-info-circle text-info"></i></a>';
            }
            if (auth()->user()->can('edit_project')) {
                $edit = '<a href="'.route('projects.edit', $each->id).'"><i class="fas fa-edit text-warning"></i></a>';
            }
            if (auth()->user()->can('delete_project')) {
                $delete = '<a href="#" data-id="'.$each->id.'" id="delete"><i class="fas fa-trash text-danger delete"></i></a>';
            }
            
            return '<div class="action-icon">'.$show.$edit.$delete.'</div>';
        })
        ->rawColumns(['leaders','members','priority','status','action'])
        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('create_project')) {
            return abort(404);
        }

        $employees = User::OrderBy('name')->get();
        return view('projects.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProject $request)
    {
        if (!auth()->user()->can('create_project')) {
            return abort(404);
        }

        $image_names = null;
        if ($request->hasFile('images')) {
            $image_names = [];
            $images_file = $request->file('images');
            foreach ($images_file as $image_file) {
                $image_name = uniqid().'_'. time().'.'.$image_file->getClientOriginalExtension();
                Storage::disk('public')->put('project/'.$image_name, file_get_contents($image_file));
                
                $image_names[] = $image_name;
            }
        }

        
        $file_names = null;

        if ($request->hasFile('files')) {
            $file_names = [];
            $files = $request->file('files');
            foreach ($files as $file) {
                $file_name = uniqid().'_'. time().'.'.$file->getClientOriginalExtension();
                Storage::disk('public')->put('project/'.$file_name, file_get_contents($file));
                
                $file_names[] = $file_name;
            }
        }


        $project =  new Project();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->images = $image_names;
        $project->files = $file_names;
        $project->startdate = $request->startdate;
        $project->deadline = $request->deadline;
        $project->priority = $request->priority;
        $project->status = $request->status;
        $project->save();
        
        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);

        return redirect()->route('projects.index')->with('create', 'An project was created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('edit_project')) {
            return abort(404);
        }
        $project = Project::find($id);
        $employees = User::OrderBy('name')->get();
        return view('projects.edit', compact('project', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProject $request, $id)
    {
        if (!auth()->user()->can('edit_project')) {
            return abort(404);
        }
        $project = Project::findOrFail($id);

        $image_names = $project->images;
        if ($request->hasFile('images')) {
            $image_names = [];
            $images_file = $request->file('images');
            foreach ($images_file as $image_file) {
                $image_name = uniqid().'_'. time().'.'.$image_file->getClientOriginalExtension();
                Storage::disk('public')->put('project/'.$image_name, file_get_contents($image_file));
                
                $image_names[] = $image_name;
            }
        }
        
        $file_names = $project->files;

        if ($request->hasFile('files')) {
            $file_names = [];
            $files = $request->file('files');
            foreach ($files as $file) {
                $file_name = uniqid().'_'. time().'.'.$file->getClientOriginalExtension();
                Storage::disk('public')->put('project/'.$file_name, file_get_contents($file));
                
                $file_names[] = $file_name;
            }
        }

        $project->title = $request->title;
        $project->description = $request->description;
        $project->images = $image_names;
        $project->files = $file_names;
        $project->startdate = $request->startdate;
        $project->deadline = $request->deadline;
        $project->priority = $request->priority;
        $project->status = $request->status;
        $project->update();
        
        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);


        return redirect()->route('projects.index')->with('update', 'An project was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete_project')) {
            return abort(404);
        }
        $project = Project::findOrFail($id);

        $project->leaders()->detach();
        $project->members()->detach();

        $project->delete();

        return 'success';
    }
}
