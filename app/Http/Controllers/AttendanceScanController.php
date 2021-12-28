<?php

namespace App\Http\Controllers;

use App\CompanySetting;
use App\CheckinCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AttendanceScanController extends Controller
{
    public function scan()
    {
        return view('scan');
    }

    public function checkin_checkout_scan(Request $request)
    {
        if (Hash::check(now()->format('Y-m-d'), $request->hash_value)) {
            $user = Auth()->user();
            if (!$user) {
                return response([
                'status' => 'fail',
                'msg' => 'passcode is wrong'
            ]);
            }

            $checkincheckoutdata = CheckinCheckout::firstOrCreate([
           'user_id' => $user->id,
           'date' => now()->format('Y-m-d')
       ]);

            if (!is_null($checkincheckoutdata->check_in_time) && !is_null($checkincheckoutdata->check_out_time)) {
                return response([
                'status' => 'fail',
                'msg'=> 'Already taken at today'
            ]);
            }

            if (is_null($checkincheckoutdata->check_in_time)) {
                $checkincheckoutdata->check_in_time = now();
                $msg = 'check in at '.now();
            } elseif (is_null($checkincheckoutdata->check_out_time)) {
                $checkincheckoutdata->check_out_time = now();
                $msg = 'check out at '.now();
            }

            $checkincheckoutdata->update();


            return response([
            'status' => 'success',
            'msg' => $msg
        ]);
        } else {
            return response([
                'status' => 'fail',
                'msg' => 'QR is invalid'
            ]);
        }
    }
}
