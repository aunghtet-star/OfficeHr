<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Department;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRole;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UpdateRole;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreEmployee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateEmployee;
use App\Http\Requests\StoreDepartment;
use App\Http\Requests\UpdateDepartment;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('view_role')) {
            return abort(404);
        }
        return view('roles.index');
    }


    public function ssd()
    {
        if (!auth()->user()->can('view_role')) {
            return abort(404);
        }
        $roles = Role::query();
        return Datatables::of($roles)
        ->addColumn('plus', function ($each) {
            return null;
        })
        ->addColumn('permission', function ($each) {
            $outline = "";
            foreach ($each->permissions->pluck('name') as $permission) {
                $outline .= '<span class="badge badge-pill badge-primary m-1">'.$permission.'</span>';
            }
            return $outline;
        })
        ->editColumn('updated_at', function ($each) {
            return Carbon::parse($each->updated_at)->format('d-m-Y h:i:s A');
        })
        ->addColumn('action', function ($each) {
            if (auth()->user()->can('edit_role')) {
                $edit = '<a href="'.route('roles.edit', $each->id).'"><i class="fas fa-edit text-warning"></i></a>';
            }

            if (auth()->user()->can('delete_role')) {
                $delete = '<a href="#" data-id="'.$each->id.'" id="delete"><i class="fas fa-trash text-danger delete"></i></a>';
            }

            
            return '<div class="action-icon">'.$edit.$delete.'</div>';
        })
        ->rawColumns(['permission','action'])
        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('create_role')) {
            return abort(404);
        }
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $request)
    {
        if (!auth()->user()->can('create_role')) {
            return abort(404);
        }
        $role =  new Role();
        $role->givePermissionTo($request->permissions);
        $role->name = $request->name;
        $role->save();

        return redirect()->route('roles.index')->with('create', 'An role was created successfully');
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
        if (!auth()->user()->can('edit_role')) {
            return abort(404);
        }
        $role = Role::find($id);
        $old_permissions = $role->permissions->pluck('id')->toArray();
       
        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'permissions', 'old_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRole $request, $id)
    {
        if (!auth()->user()->can('edit_role')) {
            return abort(404);
        }
        $role = Role::findOrFail($id);

    
        $role->name = $request->name;
        $old_permissions = $role->permissions->pluck('name')->toArray();
      
        $role->revokePermissionTo($old_permissions);
        $role->givePermissionTo($request->permissions);
        $role->update();
      

        return redirect()->route('roles.index')->with('update', 'An role was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete_role')) {
            return abort(404);
        }
        $role = Role::findOrFail($id);
        $role->delete();

        return 'success';
    }
}
