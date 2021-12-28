<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function home(){
        $employee = Auth()->user();
        return view('home',compact('employee'));
     }

    public function profile(){
        if(!auth()->user()->can('view_profile')){
            return abort(404);
         }
        $employee = Auth()->user();
        return view('profile.profile',compact('employee'));
    }

    public function biometric(){
        $employee = Auth()->user();
        $biometrics = DB::table('web_authn_credentials')->where('user_id',$employee->id)->get();
        return view('components.biometrics',compact('biometrics'))->render();

    }

    public function destroy($id){
        $employee = Auth()->user();
        DB::table('web_authn_credentials')->where('id',$id)->where('user_id',$employee->id)->delete();
        return 'success';
    }
}
