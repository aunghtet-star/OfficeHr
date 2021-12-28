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
use App\Http\Requests\StorePermission;
use App\Http\Requests\UpdateDepartment;
use App\Http\Requests\UpdatePermission;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('view_permission')){
            return abort(404);
         }
        return view('permissions.index');
    }


    public function ssd(){
        if(!auth()->user()->can('view_permission')){
            return abort(404);
         }
        $permissions = Permission::query();
        return Datatables::of($permissions)
        ->addColumn('plus',function($each){
            return null;
        })
        ->editColumn('updated_at',function($each){
            return Carbon::parse($each->updated_at)->format('d-m-Y H:i:s A');
        })
        ->addColumn('action',function($each){
            if(auth()->user()->can('edit_role')){
                $edit = '<a href="'.route('permissions.edit',$each->id).'"><i class="fas fa-edit text-warning"></i></a>';     
             }

             if(auth()->user()->can('delete_role')){
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
        if(!auth()->user()->can('create_permission')){
            return abort(404);
         }
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermission $request)
    {
        if(!auth()->user()->can('create_permission')){
            return abort(404);
         }
      $permission =  new Permission();
      $permission->name = $request->name;
    
      $permission->save();

      return redirect()->route('permissions.index')->with('create','An Permission was created successfully');
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
        if(!auth()->user()->can('edit_permission')){
            return abort(404);
         }
        $permission = Permission::find($id);
        return view('permissions.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermission $request, $id)
    {
        if(!auth()->user()->can('edit_permission')){
            return abort(404);
         }
      $permission = Permission::findOrFail($id);

    
      $permission->name = $request->name;
      
      $permission->update();

      return redirect()->route('permissions.index')->with('update','An permission was updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('delete_permission')){
            return abort(404);
         }
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return 'success';
    }
}
