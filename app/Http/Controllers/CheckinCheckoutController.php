<?php

namespace App\Http\Controllers;

use App\User;
use App\CompanySetting;
use App\CheckinCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckinCheckoutController extends Controller
{
    public function checkincheckout()
    {
        $setting = CompanySetting::find(1);
        $hash_value = Hash::make(now()->format('Y-m-d'));
        return view('checkin_checkout', compact('hash_value'));
    }

    public function checkin_and_out(Request $request)
    {
        if (now()->format('D') == 'Sat' || now()->format('D') == 'Sun') {
            return response([
                'status' => 'fail',
                'msg' => 'Today is holiday'
            ]);
        }


        $user = User::where('passcode', $request->checkin)->first();
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
    }
}
