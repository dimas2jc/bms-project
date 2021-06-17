<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\PenanggungjawabController;

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


Route::group(['middleware' => ['auth']],function(){
    Route::get('/profile', [HomeController::class, 'profile']);
    Route::get('/ganti-password', [HomeController::class, 'gantiPassword']);
    Route::post('/ganti-password', [HomeController::class, 'updatePassword']);
    Route::post('/password/verify_old_pass', 'HomeController@verify_old_password');

    /* Admin */
    Route::get('/admin/timetable', [AdminController::class, 'timetable'])->name('admin');
    Route::get('admin/reschedule', [AdminController::class, 'reschedule']);
    Route::get('admin/calendar', [AdminController::class, 'calendar']);
    Route::get('admin/user-pic', [AdminController::class, 'userPIC']);
    Route::get('admin/user-investor', [AdminController::class, 'userInvestor']);
    Route::get('admin/category-timetable', [AdminController::class, 'category']);
    Route::get('admin/category-calendar', [AdminController::class, 'category_calendar']);

    Route::post('admin/tambah-pic', [AdminController::class, 'storePIC']);
    Route::post('admin/edit-pic', [AdminController::class, 'editPIC']);
    Route::post('admin/reset-pass-pic', [AdminController::class, 'resetPassPIC']);

    Route::post('admin/tambah-investor', [AdminController::class, 'storeInvestor']);
    Route::post('admin/edit-investor', [AdminController::class, 'editInvestor']);
    Route::post('admin/reset-pass-investor', [AdminController::class, 'resetPassInvestor']);

    Route::post('admin/tambah-kategori', [AdminController::class, 'storeCategory']);
    Route::post('admin/edit-kategori', [AdminController::class, 'editCategory']);
    Route::post('admin/tambah-kategori-kalender', [AdminController::class, 'storeCategoryCalendar']);
    Route::post('admin/edit-kategori-kalender', [AdminController::class, 'editCategoryCalendar']);

    Route::get('admin/calendar/{outlet}', [AdminController::class, 'getCalendar']);
    Route::get('admin/calendar/detail/{id}', [AdminController::class, 'getDetailCalendar']);
    Route::get('admin/tambah-calendar', [AdminController::class, 'index_tambah_calendar']);
    Route::post('admin/tambah-calendar', [AdminController::class, 'storeCalendar']);
    Route::post('admin/edit-calendar', [AdminController::class, 'updateCalendar']);

    Route::get('/admin/download', [AdminController::class, 'download_file']);
    Route::get('/admin/get-file/{progress_id}', [AdminController::class, 'get_file']);
    
    // Activity
    Route::get('/activity/log-jadwal/{id_detail_activity}', [ActivityController::class, 'log_jadwal']);
    Route::get('/activity/progress/{id_detail_activity}', [ActivityController::class, 'progress']);
    Route::get('/admin/timeline/test', [ActivityController::class, 'test']);
    Route::post('/admin/activity/timeline', [ActivityController::class, 'activity_timeline']);
    Route::post('/admin/activity', [ActivityController::class, 'store']);
    Route::post('/admin/activity/reschedule', [ActivityController::class, 'reschedule']);
    

    /* Investor */
    Route::get('/investor/calendar', [InvestorController::class, 'calendar']);
    Route::get('/investor/timetable', [InvestorController::class, 'timetable'])->name('investor');
    Route::get('/investor/calendar/detail/{id}', [InvestorController::class, 'getDetailCalendar']);

    /* PIC */
    Route::get('/pic/timetable', [PenanggungjawabController::class, 'timetable'])->name('pic');
    Route::get('/pic/progress', [PenanggungjawabController::class, 'progress']);
    Route::get('/pic/detail-progress/{id}', [PenanggungjawabController::class, 'detail_progress']);
    
    Route::post('/pic/download', [PenanggungjawabController::class, 'download_file']);
    Route::post('/pic/activity/progress', [PenanggungjawabController::class, 'update_progress']);

});