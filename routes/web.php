<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    // 'register' => false
]);


Route::middleware(['auth'])->group(function () {

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/site/{id}/employees/{query?}', [App\Http\Controllers\HomeController::class, 'siteEmployees'])->name('siteEmployees');
Route::get('/employees/{id}/detail', [App\Http\Controllers\HomeController::class, 'employeeDetail'])->name('employeeDetail');
Route::get('/employee/flexibilite', [EmployeeController::class, 'flexibilityIndex'])->name('flexibilityIndex');
Route::get('/employee/absence', [EmployeeController::class, 'absenceIndex'])->name('absenceIndex');

Route::get('/export-employee-Site/{id}', [HomeController::class, 'exportSite'])->name('export.site');
Route::get('/export-employee-detail/{id}', [HomeController::class, 'exportEmployee'])->name('export.employee');

Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');
Route::get('/user/register', [RegisterController::class, 'showRegistrationForm'])->name('userRegister');
Route::get('/employee/register/form', [EmployeeController::class, 'registrationEmployeeForm'])->name('employeeRegisterForm');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');


Route::get('/employee/register/excel', [EmployeeController::class, 'showRegistrationEmployeeExcel'])->name('employeeRegister');
Route::post('/import/employee', [EmployeeController::class, 'importEmployee'])->name('importEmployee');
Route::get('/incidents/Attente', [IncidentController::class, 'index'])->name('incidents.index');
Route::get('/incidents/Accept', [IncidentController::class, 'listAccept'])->name('incidents.listAccept');
Route::get('/incidents/Reject', [IncidentController::class, 'listReject'])->name('incidents.listReject');
Route::get('/incidents/delete', [IncidentController::class, 'listToDelete'])->name('incidents.to_delete');

Route::get('/incidents/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
Route::post('/incidents/{incident}/accept', [IncidentController::class, 'accept'])->name('incidents.accept');
Route::post('/incidents/{incident}/reject', [IncidentController::class, 'reject'])->name('incidents.reject');
Route::delete('/incidents/{incident}', [IncidentController::class, 'reject'])->name('incidents.destroy');

Route::get('/profile/{id}/edit', [ProfileController::class,'show'])->name('profile.show')->middleware('auth');
Route::put('/profile/{id}', [ProfileController::class,'update'])->name('profile.update');



Route::middleware(['admin'])->group(function () {
    Route::get('/user', [UserController::class, 'Userlist'])->name('user-list');
    Route::patch('/users/{user}/deactivate', [UserController::class,'deactivateUser'])->name('deactivate.user');
    Route::patch('/users/{user}/activate', [UserController::class, 'activateUser'])->name('activate.user');
    Route::get('/employee/list', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employee/{id}/edit', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{id}', [EmployeeController::class, 'updateEmployee'])->name('employees.update');
    Route::post('/employees/{employee}/activate', [EmployeeController::class, 'activateEmployee'])->name('activate.employee');
    Route::delete('/employees/{employee}/deactivate', [EmployeeController::class, 'deleteUser'] )->name('delete.user');

});

});
