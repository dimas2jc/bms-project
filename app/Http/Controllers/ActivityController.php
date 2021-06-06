<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryActivity;
use App\Models\DetailActivity;
use App\Models\Timeplan;
use App\Models\UserLog;
use Ramsey\Uuid\Uuid;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());

        $id_outlet = $request->input_id_outlet;
        $id_detail_activity = Uuid::uuid4()->getHex();
        $id_category = $request->input_category;
        $nama_activity = $request->input_nama_activity;
        $tanggal_mulai = $request->input_tanggal_mulai;
        $tanggal_selesai = $request->input_tanggal_selesai;
        $pic = $request->input_pic;

        // Insert detail activity
        $detail_activity = new DetailActivity;
        $detail_activity->ID_DETAIL_ACTIVITY = $id_detail_activity;
        $detail_activity->ID_CATEGORY = $id_category;
        $detail_activity->NAMA_AKTIFITAS = $nama_activity;
        $detail_activity->STATUS = 0;

        if($request->has('input_event_penting')){
            $detail_activity->FLAG = 1;
        } else {
            $detail_activity->FLAG = 0;
        }

        $detail_activity->save();

        // Insert timeplan
        $timeplan = new Timeplan;
        $timeplan->ID_TIMEPLAN = Uuid::uuid4()->getHex();
        $timeplan->ID_DETAIL_ACTIVITY = $id_detail_activity;
        $timeplan->TANGGAL_START = $tanggal_mulai;
        $timeplan->TANGGAL_END = $tanggal_selesai;
        $timeplan->save();

        // Insert user_log
        $user_log = new UserLog;
        $user_log->id = Uuid::uuid4()->getHex();
        $user_log->user = $pic;
        $user_log->activity = $id_detail_activity;
        $user_log->outlet = $id_outlet;
        $user_log->save();

        return redirect('/admin/timetable')->with('toast_msg_success', 'Activity berhasil ditambahkan');
    }
    
    public function reschedule(Request $request)
    {
        $id_detail_activity = $request->id_detail_activity;
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        // Insert timeplan
        $timeplan = new Timeplan;
        $timeplan->ID_TIMEPLAN = Uuid::uuid4()->getHex();
        $timeplan->ID_DETAIL_ACTIVITY = $id_detail_activity;
        $timeplan->TANGGAL_START = $tanggal_mulai;
        $timeplan->TANGGAL_END = $tanggal_selesai;
        $timeplan->save();

        return redirect('/admin/timetable')->with('toast_msg_success', 'Reschedule berhasil');
    }

    public function activity_timeline()
    {
        $activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        for($i = 0; $i < count($activity); $i++){
            for($j = 0; $j < count($timeplan); $j++){
                if($timeplan[$j]['ID_DETAIL_ACTIVITY'] == $activity[$i]['ID_DETAIL_ACTIVITY']){
                    $activity[$i]['TIMEPLAN'] = $timeplan[$j];
                    $activity[$i]['TIMEPLAN']['TIMELINE']['YEARS'] = $this->get_activity_years(
                        $timeplan[$j]['TANGGAL_START'],
                        $timeplan[$j]['TANGGAL_END']
                    );
                    $activity[$i]['TIMEPLAN']['TIMELINE']['WEEKS'] = $this->get_activity_weeks(
                        $activity[$i]['TIMEPLAN']['TIMELINE']['YEARS'],
                        $timeplan[$j]['TANGGAL_START'],
                        $timeplan[$j]['TANGGAL_END']
                    );
                }
            }
        }

        // dd($activity);
        return $activity;
    }

    private function get_activity_years($start_date, $end_date)
    {
        $result = [];
        $start_date = date('Y', strtotime($start_date));
        $end_date = date('Y', strtotime($end_date));

        for($i = (int)$start_date; $i <= (int)$end_date; $i++){
            array_push($result, $i);
        }

        return $result;
    }
    
    private function get_activity_weeks($years, $start_date, $end_date)
    {
        $result = [];
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $start_week = date('W', strtotime($start_date));
        $end_week = date('W', strtotime($end_date));
        $diff = date_diff(date_create($start_date), date_create($end_date));

        if(count($years) == 1){
            for($i = (int)$start_week; $i <= (int)$end_week; $i++){
                array_push($result, $i);
            }
        } else {
            for($i = 0; $i < count($years); $i++){
                if($i == 0){
                    for($j = (int)$start_week; $j <= 48; $j++){
                        array_push($result, $j);
                    }
                } elseif ($i > 0 && $i != count($years) - 1) {
                    for($j = 1; $j <= 48; $j++){
                        array_push($result, $j);
                    }
                } else {
                    for($j = 1; $j <= (int)$end_week; $j++){
                        array_push($result, $j);
                    }
                }
            }
        }

        return $result;
        // return $end_week;
    }
}