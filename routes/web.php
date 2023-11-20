<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
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



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/site/{id}/employees', [App\Http\Controllers\HomeController::class, 'siteEmployees'])->name('siteEmployees');
Route::get('/employees/{id}/detail', [App\Http\Controllers\HomeController::class, 'employeeDetail'])->name('employeeDetail');

Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');


Route::middleware(['admin'])->group(function () {
    Route::get('user-list', [UserController::class, 'Userlist'])->name('user-list');
    Route::patch('/users/{user}/deactivate', [UserController::class,'deactivateUser'])->name('deactivate.user');
    Route::patch('/users/{user}/activate', [UserController::class, 'activateUser'])->name('activate.user');
    Route::get('/employees/list', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::post('/employees/{employee}/activate', [EmployeeController::class, 'activateEmployee'])->name('activate.employee');
    Route::delete('/employees/{employee}/deactivate', [EmployeeController::class, 'deactivateEmployee'] )->name('deactivate.employee');

});
Route::get('/user/register', [RegisterController::class, 'showRegistrationForm'])->name('userRegister');
Route::get('/employee/register', [RegisterController::class, 'showRegistrationEmployeeForm'])->name('employeeRegister');
