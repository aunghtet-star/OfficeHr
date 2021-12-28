<?php

namespace App\Http\Controllers;

use App\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Http\Requests\UpdateCompanySetting;

class CompanySettingController extends Controller
{
    public function show($id)
    {
        if (!auth()->user()->can('view_company_setting')) {
            return abort(404);
        }
        $company_setting =  CompanySetting::findOrFail($id);
        return view('company_settings.show', compact('company_setting'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit_company_setting')) {
            return abort(404);
        }
        $company_setting =  CompanySetting::findOrFail($id);
        return view('company_settings.edit', compact('company_setting'));
    }

    public function update(UpdateCompanySetting $request, $id)
    {
        if (!auth()->user()->can('edit_company_setting')) {
            return abort(404);
        }

        $company_setting = CompanySetting::findOrFail($id);
        $company_setting->company_name = $request->company_name;
        $company_setting->company_email = $request->company_email;
        $company_setting->company_phone = $request->company_phone;
        $company_setting->company_address = $request->company_address;
        $company_setting->office_start_time = $request->office_start_time;
        $company_setting->office_end_time = $request->office_end_time;
        $company_setting->break_start_time = $request->break_start_time;
        $company_setting->break_end_time = $request->break_end_time;

        $company_setting->update();

        return redirect()->route('company_settings.show', 1)->with('update', 'A company setting was updated successfully');
    }
}
