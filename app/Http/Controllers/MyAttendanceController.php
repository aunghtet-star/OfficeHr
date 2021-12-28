<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\CompanySetting;
use App\CheckinCheckout;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyAttendanceController extends Controller
{
    public function ssd(Request $request)
    {
        $attendances = CheckinCheckout::with('employee')->where('user_id', Auth()->user()->id);
        
        if ($request->month) {
            $attendances = $attendances->whereMonth('date', $request->month);
        }

        if ($request->year) {
            $attendances = $attendances->whereYear('date', $request->year);
        }
        return Datatables::of($attendances)
        
        ->addColumn('plus', function ($each) {
            return null;
        })
        ->addColumn('employee', function ($each) {
            return $each->employee ? $each->employee->name : '_  ' ;
        })
        ->make(true);
    }

    public function overviewTable(Request $request)
    {
        $month = $request->month;
        $year = $request->year;


        $startofMonth = $year.'-'.$month.'-'.'01';
        $endofMonth = Carbon::parse($startofMonth)->endOfMonth()->format('Y-m-d');

        $users = User::orderBy('employee_id')->where('id', Auth()->user()->id)->get();
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod($startofMonth, $endofMonth);
        
        $attendances = CheckinCheckout::whereMonth('date', $month)->whereYear('date', $year)->get();
        return view('components.attendance_overview', compact('users', 'company_setting', 'periods', 'attendances'))->render();
    }
}
