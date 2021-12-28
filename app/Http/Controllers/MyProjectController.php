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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyProjectController extends Controller
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
        return view('my_project');
    }


    public function ssd()
    {
        if (!auth()->user()->can('view_project')) {
            return abort(404);
        }
        $projects = Project::with('leaders', 'members')
        ->whereHas('leaders', function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->orWhereHas('members', function ($query) {
            $query->where('user_id', Auth()->user()->id);
        });
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
           
            if (auth()->user()->can('view_project')) {
                $show = '<a href="'.route('my-projects.show', $each->id).'"><i class="fas fa-info-circle text-info"></i></a>';
            }
          
            
            return '<div class="action-icon">'.$show.'</div>';
        })
        ->rawColumns(['leaders','members','priority','status','action'])
        ->make(true);
    }

    public function show($id)
    {
        $project = Project::with('leaders', 'members', 'tasks')
        ->where('id', $id)
        ->whereHas('leaders', function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->orwhereHas('members', function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->findOrFail($id);
        return view('my_project_show', compact('project'));
    }
}
