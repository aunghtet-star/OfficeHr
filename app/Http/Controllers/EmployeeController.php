<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreEmployee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateEmployee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('view_employee')){
           return abort(404);
        }
        return view('employees.index');
    }


    public function ssd(){
        if(!auth()->user()->can('view_employee')){
            return abort(404);
         }
        $employees = User::with('department');
        return Datatables::of($employees)
        ->filterColumn('department',function($query,$keyword){
            $query->whereHas('department',function($q1) use($keyword){
                $q1->where('title','like','%'.$keyword.'%');
            });
        })
        ->addColumn('plus',function($each){
            return null;
        })
        ->addColumn('profile_img',function($each){
            $profile_img = '<img src=" '.$each->profile_image_path().'" class="thumbnail"><p class="my-1">'.$each->name.'</p>';
            return $profile_img;
        })
        ->addColumn('department',function($each){
            $department = $each->department ? $each->department->title : '-';
            return $department;
        })
        ->addColumn('role',function($each){
            $output ='';
            foreach($each->roles->pluck('name') as $role){
                $output .= '<span class="badge badge-pill badge-primary mr-1">'.$role.'</span>';
            }
             return $output;
        })
        ->editColumn('updated_at',function($each){
            return Carbon::parse($each->updated_at)->format('d-m-Y H:i:s A');
        })
        ->editColumn('is_present',function($each){
            if($each->is_present == 1){
                return '<button class="badge badge-pill badge-success">Live</button>';
            }else{
                return '<button class="badge badge-pill badge-danger">Out</button>';
            }
        })
        ->addColumn('action',function($each){
            if(auth()->user()->can('edit_employee')){
                $edit = '<a href="'.route('employees.edit',$each->id).'"><i class="fas fa-edit text-warning"></i></a>';                
             }
             if(auth()->user()->can('view_employee')){
                 $show = '<a href="'.route('employees.show',$each->id).'"><i class="fas fa-info text-info"></i></a>';                
                }
                if(auth()->user()->can('delete_employee')){
                    $delete = '<a href="#" data-id="'.$each->id.'" id="delete"><i class="fas fa-trash text-danger delete"></i></a>';                   
             }
            
            return '<div class="action-icon">'.$edit.$show.$delete.'</div>';
        })
        ->rawColumns(['profile_img','role','is_present','action'])
        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('create_employee')){
            return abort(404);
         }
       $departments =  Department::Orderby('title')->get();
       $roles = Role::all();

        return view('employees.create',compact('departments','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployee $request)
    {
        if(!auth()->user()->can('create_employee')){
            return abort(404);
         }
      $profile_img_name = null;
      if($request->hasFile('profile_img')){
          $profile_img_file = $request->file('profile_img');
          $profile_img_name = uniqid().'_'. time().'.'.$profile_img_file->getClientOriginalExtension();
          Storage::disk('public')->put('employee/'.$profile_img_name,file_get_contents($profile_img_file));
      }


      $employee =  new User();
      $employee->name = $request->name;
      $employee->email = $request->email;
      $employee->phone = $request->phone;
      $employee->nrc_number = $request->nrc_number;
      $employee->employee_id = $request->employee_id;
      $employee->department_id = $request->department_id;
      $employee->gender = $request->gender;
      $employee->address = $request->address;
      $employee->birthday = $request->birthday;
      $employee->date_of_join = $request->date_of_join;
      $employee->is_present = $request->is_present;
      $employee->profile_img = $profile_img_name;
      $employee->passcode = $request->passcode;
      $employee->password = Hash::make($request->password);

      $employee->save();

      $employee->assignRole($request->roles);

      return redirect()->route('employees.index')->with('create','An employee was created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!auth()->user()->can('view_employee')){
            return abort(404);
         }
        $employee = User::findOrFail($id);
        return view('employees.show',compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->can('edit_employee')){
            return abort(404);
         }
        $employee = User::find($id);
        $departments = Department::all();
        $roles = Role::all();
        $old_roles = $employee->getRoleNames()->toArray();
        
        return view('employees.edit',compact('employee','departments','roles','old_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployee $request, $id)
    {
        if(!auth()->user()->can('edit_employee')){
            return abort(404);
         }
      $employee = User::findOrFail($id);

      $profile_img_name = $employee->profile_img;
        if($request->hasFile('profile_img')){

            Storage::disk('public')->delete('employee/'.$employee->profile_img);

            $profile_img_file = $request->file('profile_img');
            $profile_img_name = uniqid().'_'. time().'.'.$profile_img_file->getClientOriginalExtension();
            Storage::disk('public')->put('employee/'.$profile_img_name,file_get_contents($profile_img_file));
        }
      $employee->name = $request->name;
      $employee->email = $request->email;
      $employee->phone = $request->phone;
      $employee->employee_id = $request->employee_id;
      $employee->department_id = $request->department_id;
      $employee->gender = $request->gender;
      $employee->date_of_join = $request->date_of_join;
      $employee->birthday = $request->birthday;
      $employee->is_present = $request->is_present;
      $employee->address = $request->address;
      $employee->nrc_number = $request->nrc_number;
      $employee->profile_img = $profile_img_name;
      $employee->passcode = $request->passcode;

      $employee->password = $request->password ? Hash::make($request->password) : $employee->password;
      
      $employee->update();

      $employee->syncRoles($request->roles);
      return redirect()->route('employees.index')->with('update','An employee was updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('delete_employee')){
            return abort(404);
         }
        $employee = User::findOrFail($id);
        $employee->delete();

        return 'success';
    }
}
