<?php

namespace Database\Seeders;

use App\CheckinCheckout;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            $periods = new CarbonPeriod('2020-01-01', '2021-12-30');
            foreach ($periods as $period) {
                if ($period->format('D') != 'Sat' && $period->format('D') != 'Sun') {
                    $attendance = new CheckinCheckout();
                    $attendance->user_id = $user->id;
                    $attendance->date = $period->format('Y-m-d');
                    $attendance->check_in_time = Carbon::parse($period->format('Y-m-d').' '.'09:00:00')->subMinutes(rand(1, 55));
                    $attendance->check_out_time = Carbon::parse($period->format('Y-m-d').' '.'18:00:00')->addMinutes(rand(1, 55));
                    $attendance->save();
                }
            }
        }
    }
}
