<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => 'auth'], function() {
    Route::get('admin/dashboard', DashboardController::class)->name('admin.dashboard');

    Route::get('admin/employees', \App\Http\Livewire\Admin\Employees\ListEmployees::class)->name('admin.employees');

    Route::get('admin/positions', \App\Http\Livewire\Admin\Positions\ListPositions::class)->name('admin.positions');
});




//Route::post('/login', [])->name('login');
//Route::get('/logout', [])->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
