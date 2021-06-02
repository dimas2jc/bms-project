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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvestorController extends Controller
{
    public function calendar()
    {
        $outlet = Outlet::all()->toArray();
        $category_activity = CategoryActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $detail_activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        for($i = 0; $i < count($detail_activity); $i++){
            for($j = 0; $j < count($timeplan); $j++){
                if($timeplan[$j]['ID_DETAIL_ACTIVITY'] == $detail_activity[$i]['ID_DETAIL_ACTIVITY']){
                    $tanggal_mulai = $timeplan[$j]['TANGGAL_START'];
                    $tanggal_selesai = $timeplan[$j]['TANGGAL_END'];
                    $durasi = date_diff(date_create($tanggal_mulai), date_create($tanggal_selesai));

                    $detail_activity[$i]['TANGGAL_START'] = date('Y-m-d', strtotime($tanggal_mulai));
                    $detail_activity[$i]['TANGGAL_END'] = date('Y-m-d', strtotime($tanggal_selesai));
                    $detail_activity[$i]['DURASI'] = $durasi;
                }
            }
        }

        return view('investor.calendar', compact('category_activity', 'detail_activity', 'outlet', 'timeplan'));
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

                    $detail_activity[$i]['TANGGAL_START'] = date('d-m-Y', strtotime($tanggal_mulai));
                    $detail_activity[$i]['TANGGAL_END'] = date('d-m-Y', strtotime($tanggal_selesai));
                    $detail_activity[$i]['DURASI'] = $durasi;
                    $detail_activity[$i]['DEADLINE'] = $deadline;

                    break;
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
        }

        return view('investor.timetable', compact('category_activity', 'detail_activity', 'outlet', 'pic', 'timeplan'));
    }
}
