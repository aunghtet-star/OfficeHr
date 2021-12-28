<?php

use App\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new CompanySetting();
        $setting->company_name = 'AorK Office';
        $setting->company_email = 'aork@company.com';
        $setting->company_phone = '09969861379';
        $setting->company_address = 'Shwebo st,Pyin Oo Lwin Township';
        $setting->office_start_time = '9:00:00 AM';
        $setting->office_end_time = '5:00:00 PM';
        $setting->break_start_time = '12:00:00 PM';
        $setting->break_end_time = '1:00:00 pM';
        $setting->save();
    }
}
