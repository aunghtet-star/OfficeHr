<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\CompanySetting;
use App\CheckinCheckout;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyPayrollController extends Controller
{
    public function ssd(Request $request)
    {
        $users = User::orderBy('employee_id')->get();
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod('2021-01-01', '2021-01-30');
        
        $attendances = CheckinCheckout::whereMonth('date', '01')->whereYear('date', '2021')->get();
        return view('payroll', compact('users', 'company_setting', 'periods', 'attendances'));
    }

    public function MypayrollTable(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $employee_name = $request->employee_name;

        $startofMonth = $year.'-'.$month.'-'.'01';
        $endofMonth = Carbon::parse($startofMonth)->endOfMonth()->format('Y-m-d');

        $dayofMonth = Carbon::parse($startofMonth)->daysInMonth;

        $period = CarbonPeriod::between($startofMonth, $endofMonth)->filter('isWeekday');
        foreach ($period as $date) {
            $days[] = $date->format('Y-m-d');
        }
        
        $workingDay = count($days);
        $users = User::where('id', Auth()->user()->id)->get();
       
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod($startofMonth, $endofMonth);
        
        $attendances = CheckinCheckout::whereMonth('date', $month)->whereYear('date', $year)->get();
        return view('components.payroll_table', compact('users', 'company_setting', 'periods', 'attendances', 'dayofMonth', 'workingDay', 'month', 'year'))->render();
    }
}
