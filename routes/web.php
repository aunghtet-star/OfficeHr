<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Controllers\Auth\WebAuthnLoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])
     ->name('webauthn.register.options');
Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])
     ->name('webauthn.register');

Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])
     ->name('webauthn.login.options');
Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])
     ->name('webauthn.login');


Auth::routes(['register' => false]);

Route::get('login-options', 'Auth\LoginController@loginoption')->name('login_option');

Route::get('checkincheckout', 'CheckinCheckoutController@checkincheckout')->name('checkincheckout');
Route::post('checkin_and_out', 'CheckinCheckoutController@checkin_and_out');

Route::middleware('auth')->group(function () {
    Route::get('/', 'PageController@home')->name('home');

    Route::resource('employees', 'EmployeeController');
    Route::get('employees/datatables/ssd', 'EmployeeController@ssd');

    Route::get('profile', 'PageController@profile')->name('profile');
    Route::delete('bio-finger-btn/destroy/{id}', 'PageController@destroy')->name('bio-finger-btn-destroy');

    Route::resource('departments', 'DepartmentController');
    Route::get('departments/datatables/ssd', 'DepartmentController@ssd');

    Route::resource('roles', 'RoleController');
    Route::get('roles/datatables/ssd', 'RoleController@ssd');

    Route::resource('permissions', 'PermissionController');
    Route::get('permissions/datatables/ssd', 'PermissionController@ssd');

    Route::resource('company_settings', 'CompanySettingController')->only('show', 'edit', 'update');

    Route::get('biometrics/options', 'PageController@biometric');

    Route::resource('attendances', 'AttendanceController');
    Route::get('attendances/datatables/ssd', 'AttendanceController@ssd');
    Route::get('attendances/overview/dr', 'AttendanceController@overview')->name('attendances.overview');
    Route::get('attendances/overview/table', 'AttendanceController@overviewTable');

    Route::get('attendance-scan', 'AttendanceScanController@scan')->name('attendance.scan');
    Route::post('attendance-scan/checkin_checkout_scan', 'AttendanceScanController@checkin_checkout_scan');
    Route::get('my-attendances/datatables/ssd', 'MyAttendanceController@ssd');
    Route::get('my-attendances/overview/table', 'MyAttendanceController@overviewTable');

    Route::resource('salaries', 'SalaryController');
    Route::get('salaries/datatables/ssd', 'SalaryController@ssd');

    Route::get('payroll/dr', 'PayrollController@payroll')->name('payroll.overview');
    Route::get('payroll/table', 'PayrollController@payrollTable');
    Route::get('my-payroll/dr', 'MyPayrollController@ssd');
    Route::get('my-payroll/table', 'MyPayrollController@MypayrollTable');
    
    Route::resource('projects', 'ProjectController');
    Route::get('projects/datatables/ssd', 'ProjectController@ssd');
    Route::resource('my-projects', 'MyProjectController')->only(['index','show']);
    Route::get('my-projects/datatables/ssd', 'MyProjectController@ssd');

    Route::resource('tasks', 'TaskController');
    Route::get('tasks-data', 'TaskController@taskData');
    Route::get('tasks-draggable', 'TaskController@taskDraggable');
});
