<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Department;
use App\CheckinCheckout;
use App\CompanySetting;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreEmployee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateEmployee;
use App\Http\Requests\StoreAttendance;
use App\Http\Requests\StoreDepartment;
use App\Http\Requests\UpdateDepartment;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('view_attendance')) {
            return abort(404);
        }
        return view('attendances.index');
    }


    public function ssd()
    {
        if (!auth()->user()->can('view_attendance')) {
            return abort(404);
        }
        $attendances = CheckinCheckout::with('employee');
        return Datatables::of($attendances)
        ->filterColumn('employee', function ($query, $keyword) {
            $query->whereHas('employee', function ($q1) use ($keyword) {
                $q1->where('name', 'like', '%'.$keyword.'%');
            });
        })
        ->addColumn('plus', function ($each) {
            return null;
        })
        ->addColumn('employee', function ($each) {
            return $each->employee ? $each->employee->name : '_  ' ;
        })
        ->editColumn('updated_at', function ($each) {
            return Carbon::parse($each->updated_at)->format('d-m-Y H:i:s A');
        })
        ->addColumn('action', function ($each) {
            if (auth()->user()->can('edit_attendance')) {
                $edit = '<a href="'.route('attendances.edit', $each->id).'"><i class="fas fa-edit text-warning"></i></a>';
            }
            if (auth()->user()->can('delete_attendance')) {
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
        if (!auth()->user()->can('create_attendance')) {
            return abort(404);
        }
        $employees = User::all();
        return view('attendances.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendance $request)
    {
        if (!auth()->user()->can('create_attendance')) {
            return abort(404);
        }
      
        if (CheckinCheckout::where('user_id', $request->user_id)->where('date', $request->date)->exists()) {
            return back()->withErrors('Already Exists')->withInput();
        }
        $attendance =  new CheckinCheckout();
        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->check_in_time = $request->date.' '. $request->check_in_time;
        $attendance->check_out_time = $request->date.' '. $request->check_out_time;
    
        $attendance->save();

        return redirect()->route('attendances.index')->with('create', 'An attendance was created successfully');
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
        if (!auth()->user()->can('edit_attendance')) {
            return abort(404);
        }
        $employees = User::all();

        $attendance = CheckinCheckout::find($id);
        
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit_attendance')) {
            return abort(404);
        }
        $attendance = CheckinCheckout::findOrFail($id);

        if (CheckinCheckout::where('user_id', $request->user_id)->where('date', $request->date)->where('date', '!=', $attendance->date)->exists()) {
            return back()->withErrors('Already Exists')->withInput();
        }
    
        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->check_in_time = $request->date.' '. $request->check_in_time;
        $attendance->check_out_time = $request->date.' '. $request->check_out_time;
      
        $attendance->update();

        return redirect()->route('attendances.index')->with('update', 'An attendance was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete_attendance')) {
            return abort(404);
        }
        $attendance = CheckinCheckout::findOrFail($id);
        $attendance->delete();

        return 'success';
    }

    public function overview(Request $request)
    {
        if (!auth()->user()->can('view_attendance_overview')) {
            return abort(404);
        }
        $users = User::orderBy('employee_id')->get();
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod('2021-01-01', '2021-01-30');
        
        $attendances = CheckinCheckout::whereMonth('date', '01')->whereYear('date', '2021')->get();
        return view('attendances.overview', compact('users', 'company_setting', 'periods', 'attendances'));
    }

    public function overviewTable(Request $request)
    {
        if (!auth()->user()->can('view_attendance_overview')) {
            return abort(404);
        }
        $month = $request->month;
        $year = $request->year;
        $employee_name = $request->employee_name;

        $startofMonth = $year.'-'.$month.'-'.'01';
        $endofMonth = Carbon::parse($startofMonth)->endOfMonth()->format('Y-m-d');

        $users = User::orderBy('employee_id')->where('name', 'like', '%'.$employee_name.'%')->get();
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod($startofMonth, $endofMonth);
        
        $attendances = CheckinCheckout::whereMonth('date', $month)->whereYear('date', $year)->get();
        return view('components.attendance_overview', compact('users', 'company_setting', 'periods', 'attendances'))->render();
    }
}
