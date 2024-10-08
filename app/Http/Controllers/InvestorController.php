<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use App\Models\CategoryActivity;
use App\Models\DetailActivity;
use App\Models\Outlet;
use App\Models\Timeplan;
use App\Models\Progress;
use App\Models\UserLog;
use App\Models\DetailCategoryCalendar;
use App\Models\CategoryCalendar;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;

class InvestorController extends Controller
{
    public function calendar()
    {
        $investor = Auth::user()->ID_USER;
        $log = UserLog::where('user', '=', $investor)->first();
        $outlet = Outlet::where('ID_OUTLET', '=', $log->outlet)->first();

        $detail_category = DetailCategoryCalendar::where('ID_OUTLET', '=', $log->outlet)->get()->toArray();
        $category_calendar = CategoryCalendar::orderBy('created_at', 'ASC')->get()->toArray();
        $calendar = Calendar::orderBy('created_at', 'ASC')->get()->toArray();

        $category = [];
        for($i = 0; $i < count($detail_category); $i++){
            for($j = 0; $j < count($category_calendar); $j++){
                if($category_calendar[$j]['ID_CATEGORY_CALENDAR'] == $detail_category[$i]['ID_CATEGORY_CALENDAR']){
                    $category[] = $category_calendar[$j];
                }
            }
        }

        $data = [];
        for($k = 0; $k < count($category); $k++){
            for($l = 0; $l < count($calendar); $l++){
                if($calendar[$l]['ID_CATEGORY_CALENDAR'] == $category[$k]['ID_CATEGORY_CALENDAR']){
                    $calendar[$l]['CATEGORY'] = $category[$k]['NAMA'];
                    $tanggal_mulai = $calendar[$l]['TANGGAL_START'];
                    $tanggal_selesai = $calendar[$l]['TANGGAL_END'];
                    $durasi = date_diff(date_create($tanggal_mulai), date_create($tanggal_selesai));
                    
                    $data[$l] = $calendar[$l];
                    $data[$l]['TANGGAL_START'] = date('Y-m-d', strtotime($tanggal_mulai));
                    $data[$l]['TANGGAL_END'] = date('Y-m-d', strtotime($tanggal_selesai));
                    $data[$l]['DURASI'] = $durasi;
                }
            }
        }

        return view('investor.calendar', compact('data', 'outlet'));
    }

    public function getDetailCalendar($id)
    {
        $data = Calendar::where('ID_CALENDAR', '=', $id)->first();

        return view('investor.detail-calendar', compact('data'));
    }

    public function timetable()
    {
        $outlet = Outlet::all()->toArray();
        $category_activity = CategoryActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $pic = User::where('ROLE', 2)->get()->toArray();
        $detail_activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        for($i = 0; $i < count($detail_activity); $i++){
            for($j = 0; $j < count($timeplan); $j++){
                if($timeplan[$j]['ID_DETAIL_ACTIVITY'] == $detail_activity[$i]['ID_DETAIL_ACTIVITY']){
                    $tanggal_mulai = $timeplan[$j]['TANGGAL_START'];
                    $tanggal_selesai = $timeplan[$j]['TANGGAL_END'];
                    $durasi = date_diff(date_create($tanggal_mulai), date_create($tanggal_selesai));
                    $deadline = date_diff(date_create(Carbon::now($tz = 'Asia/Jakarta')), date_create($tanggal_selesai));

                    $detail_activity[$i]['TANGGAL_START'] = date('Y-m-d', strtotime($tanggal_mulai));
                    $detail_activity[$i]['TANGGAL_END'] = date('Y-m-d', strtotime($tanggal_selesai));
                    $detail_activity[$i]['DURASI'] = $durasi;
                    $detail_activity[$i]['DEADLINE'] = $deadline;

                    // break;
                }
            }

            for($k = 0; $k < count($category_activity); $k++){
                if($category_activity[$k]['ID_CATEGORY'] == $detail_activity[$i]['ID_CATEGORY']){
                    $detail_activity[$i]['CATEGORY'] = $category_activity[$k]['NAMA'];
                    $detail_activity[$i]['ID_OUTLET'] = $category_activity[$k]['ID_OUTLET'];

                    break;
                }
            }

            for($l = 0; $l < count($outlet); $l++){
                if($outlet[$l]['ID_OUTLET'] == $detail_activity[$i]['ID_OUTLET']){
                    $detail_activity[$i]['OUTLET'] = $outlet[$l]['NAMA'];
                    break;
                }
            }

            $pic_id = DB::table('user_log')->where('activity', $detail_activity[$i]['ID_DETAIL_ACTIVITY'])->value('user');
            $pic_name = DB::table('user')->where('ID_USER', $pic_id)->value('NAMA');

            $detail_activity[$i]['PIC'] = $pic_name;

            $detail_activity[$i]['STATUS'] = $this->check_activity_status(
                $detail_activity[$i]['TANGGAL_START'],
                $detail_activity[$i]['TANGGAL_END'],
                $detail_activity[$i]['STATUS']
            );

            // Progress
            if(DB::table('progress')->where('ID_DETAIL_ACTIVITY', $detail_activity[$i]['ID_DETAIL_ACTIVITY'])->exists()){

                $progress = DB::table('progress')->orderByDesc('created_at')->first();
                $detail_activity[$i]['PROGRESS'] = $progress->PROGRESS;

            } else {
                $detail_activity[$i]['PROGRESS'] = 0;
            }
        }

        return view('investor.timetable', compact('category_activity', 'detail_activity', 'outlet', 'pic', 'timeplan'));
    }

    private function check_activity_status($start_date, $end_date, $status)
    {
        $result = '';
        $today = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $diff_now_to_start = date_diff(
            date_create($today),
            date_create($start_date)
        );
        
        $diff_now_to_end = date_diff(
            date_create($today),
            date_create($end_date)
        );

        $diff_start_to_end = date_diff(
            date_create($start_date),
            date_create($end_date)
        );

        if((int)$status == 0){

            if($diff_now_to_start->days > 0 && $diff_now_to_start->invert == 0){
                $result = 0;
            } elseif ($diff_now_to_start->days >= 0 && $diff_now_to_start->invert == 1){
                if($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 0){
                    if($diff_start_to_end->days > 10 && $diff_now_to_end->days <= 10 && $diff_now_to_end->invert == 0){
                        $result = 1;
                    } else {
                        $result = 1;
                    }
                } elseif ($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 1){
                    $result = 2;
                }
            } elseif ($diff_now_to_start->days >= 0 && $diff_now_to_start->invert == 0){
                if($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 0){
                    if($diff_start_to_end->days > 10 && $diff_now_to_end->days <= 10 && $diff_now_to_end->invert == 0){
                        $result = 1;
                    } else {
                        $result = 1;
                    }
                } elseif ($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 1){
                    $result = 2;
                }
            }

        } elseif((int)$status == 1){
            $result = 3;
        }

        return $result;
    }
}
