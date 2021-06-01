<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActivityController;

use Ramsey\Uuid\Uuid;

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

Route::get('guid', function () {
    if(!empty($_GET['c'])){
        for($i=0; $i<$_GET['c']; $i++)
        echo Uuid::uuid4().'<br>';
    }else{
        echo Uuid::uuid4();
    }
});

Route::get('/', [HomeController::class, 'authenticate'])->name('login');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
Route::post('/post-login', [HomeController::class, 'postLogin']);


/* Admin */
Route::group(['middleware' => ['auth']],function(){
    Route::get('/profile', [HomeController::class, 'profile']);
    Route::get('/ganti-password', [HomeController::class, 'gantiPassword']);
    Route::post('/ganti-password', [HomeController::class, 'updatePassword']);
    Route::post('/password/verify_old_pass', 'HomeController@verify_old_password');

    Route::get('/admin/timetable', [AdminController::class, 'timetable'])->name('admin');
    Route::get('admin/reschedule', [AdminController::class, 'reschedule']);
    Route::get('admin/calendar', [AdminController::class, 'calendar']);
    Route::get('admin/user-pic', [AdminController::class, 'userPIC']);
    Route::get('admin/user-investor', [AdminController::class, 'userInvestor']);
    Route::get('admin/category', [AdminController::class, 'category']);

    Route::post('admin/tambah-pic', [AdminController::class, 'storePIC']);
    Route::post('admin/edit-pic', [AdminController::class, 'editPIC']);
    Route::post('admin/reset-pass-pic', [AdminController::class, 'resetPassPIC']);

    Route::post('admin/tambah-investor', [AdminController::class, 'storeInvestor']);
    Route::post('admin/edit-investor', [AdminController::class, 'editInvestor']);
    Route::post('admin/reset-pass-investor', [AdminController::class, 'resetPassInvestor']);

    Route::post('admin/tambah-kategori', [AdminController::class, 'storeCategory']);
    Route::post('admin/edit-kategori', [AdminController::class, 'editCategory']);

    Route::post('admin/calendar/update', [AdminController::class, 'updateCalendar']);






    // Activity
    Route::post('/admin/activity', [ActivityController::class, 'store']);
    Route::put('/admin/activity', [ActivityController::class, 'update']);

});