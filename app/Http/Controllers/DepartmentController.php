<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreEmployee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateEmployee;
use App\Http\Requests\StoreDepartment;
use App\Http\Requests\UpdateDepartment;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('view_department')) {
            return abort(404);
        }
        return view('departments.index');
    }


    public function ssd()
    {
        if (!auth()->user()->can('view_department')) {
            return abort(404);
        }
        $departments = Department::query();
        return Datatables::of($departments)
        ->addColumn('plus', function ($each) {
            return null;
        })
        ->editColumn('updated_at', function ($each) {
            return Carbon::parse($each->updated_at)->format('d-m-Y H:i:s A');
        })
        ->addColumn('action', function ($each) {
            if (auth()->user()->can('edit_department')) {
                $edit = '<a href="'.route('departments.edit', $each->id).'"><i class="fas fa-edit text-warning"></i></a>';
            }
            if (auth()->user()->can('delete_department')) {
                $delete = '<a href="#" data-id="'.$each->id.'" id="delete"><i class="fas fa-trash text-danger delete"></i></a>';
            }
            
            return '<div class="action-icon">'.$edit.$delete.'</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('create_department')) {
            return abort(404);
        }
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDepartment $request)
    {
        if (!auth()->user()->can('create_department')) {
            return abort(404);
        }
        $department =  new Department();
        $department->title = $request->title;
    
        $department->save();

        return redirect()->route('departments.index')->with('create', 'An department was created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('edit_department')) {
            return abort(404);
        }
        $department = Department::find($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDepartment $request, $id)
    {
        if (!auth()->user()->can('edit_department')) {
            return abort(404);
        }
        $department = Department::findOrFail($id);

    
        $department->title = $request->title;
      
        $department->update();

        return redirect()->route('departments.index')->with('update', 'An department was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete_department')) {
            return abort(404);
        }
        $department = Department::findOrFail($id);
        $department->delete();

        return 'success';
    }
}
