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
    
    public function log_jadwal($id_detail_activity)
    {
        $id = $id_detail_activity;
        $result = DB::table('timeplan')->where('ID_DETAIL_ACTIVITY', $id)
                    ->orderByDesc('created_at')->get()->toArray();

        return $result;
    }
    
    public function progress($id_detail_activity)
    {
        $id = $id_detail_activity;
        $result = DB::table('progress')->where('ID_DETAIL_ACTIVITY', $id)
                    ->orderByDesc('created_at')->get()->toArray();

        return $result;
    }
    
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

    public function activity_timeline(Request $request)
    {
        $result = [];
        $tahun = $request->tahun;
        $id_outlet = $request->id_outlet;
        $activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        // Detail activity
        for($i = 0; $i < count($activity); $i++){
            $tmp_id_category = $activity[$i]['ID_CATEGORY'];
            $tmp_id_outlet = DB::table('category_activity')->where('ID_CATEGORY', $tmp_id_category)
                                ->value('ID_OUTLET');

            if($tmp_id_outlet == $id_outlet){
                array_push($result, $activity[$i]);
            }
        }

        // Timeplan
        for($i = 0; $i < count($result); $i++){
            $tmp_timeplan = [];

            for($j = 0; $j < count($timeplan); $j++){
                if($timeplan[$j]['ID_DETAIL_ACTIVITY'] == $result[$i]['ID_DETAIL_ACTIVITY']){
                    array_push($tmp_timeplan, $timeplan[$j]);
                }
            }

            $result[$i]['TIMEPLAN'] = $tmp_timeplan;
        }

        // Test timeline
        for($i = 0; $i < count($result); $i++){
            $result[$i]['TIMELINE'] = [];

            for($j = 0; $j < count($result[$i]['TIMEPLAN']); $j++){
                $tmp_start_date = date('Y', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                $tmp_end_date = date('Y', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                
                if($j == count($result[$i]['TIMEPLAN']) - 1){
                    $result[$i]['TIMELINE_COLOR'] = $this->timeline_color(
                        $result[$i]['TIMEPLAN'][$j]['TANGGAL_START'],
                        $result[$i]['TIMEPLAN'][$j]['TANGGAL_END'],
                        $result[$i]['STATUS']
                    );
                }

                // Jika tahun mulai dan tahun selesai sama
                if($tmp_start_date == $tmp_end_date){
                    if($tmp_end_date == date('Y', strtotime($tahun))){
                        $tmp_start_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $tmp_end_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                        $tmp_start_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $tmp_end_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));

                        $a = (int)$tmp_start_month;
                        $b = (int)$tmp_end_month;
                        $x = (int)$tmp_start_month_date;
                        $y = (int)$tmp_end_month_date;
                        $weeks = [];
                        
                        if(1 <= $x && $x <= 7){
                            $tmp_start_week = ($a * 4) - 3;
                        } elseif (8 <= $x && $x <= 14){
                            $tmp_start_week = ($a * 4)  - 2;
                        } elseif (15 <= $x && $x <= 21){
                            $tmp_start_week = ($a * 4)  - 1;
                        } elseif (22 <= $x && $x <= 31){
                            $tmp_start_week = ($a * 4) ;
                        }
                        
                        if(1 <= $y && $y <= 7){
                            $tmp_end_week = ($b * 4) - 3;
                        } elseif (8 <= $y && $y <= 14){
                            $tmp_end_week = ($b * 4)  - 2;
                        } elseif (15 <= $y && $y <= 21){
                            $tmp_end_week = ($b * 4)  - 1;
                        } elseif (22 <= $y && $y <= 31){
                            $tmp_end_week = ($b * 4) ;
                        }

                        for($k = $tmp_start_week; $k <= $tmp_end_week; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);
                        // $result[$i]['TIMELINE'][$j]['WEEKS'] = $weeks;
                    } else {
                        $weeks = [];
                    }

                } else { // Jika tahun mulai dan tahun selesai beda

                    // jika input request tahun = tahun start
                    if( $tmp_start_date === date('Y', strtotime($tahun)) ){
                        $tmp_start_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $tmp_start_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $x = (int)$tmp_start_month;
                        $y = (int)$tmp_start_month_date;
                        $weeks = [];

                        if(1 <= $y && $y <= 7){
                            $tmp_start_week = ($x * 4) - 3;
                        } elseif (8 <= $y && $y <= 14){
                            $tmp_start_week = ($x * 4)  - 2;
                        } elseif (15 <= $y && $y <= 21){
                            $tmp_start_week = ($x * 4)  - 1;
                        } elseif (22 <= $y && $y <= 31){
                            $tmp_start_week = ($x * 4) ;
                        }

                        for($k = $tmp_start_week; $k <= 48; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);

                    } elseif( $tmp_end_date === date('Y', strtotime($tahun)) ){
                        // jika input request tahun = tahun end

                        $tmp_end_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                        $tmp_end_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                        $x = (int)$tmp_end_month;
                        $y = (int)$tmp_end_month_date;
                        $weeks = [];

                        if(1 <= $y && $y <= 7){
                            $tmp_end_week = ($x * 4) - 3;
                        } elseif (8 <= $y && $y <= 14){
                            $tmp_end_week = ($x * 4)  - 2;
                        } elseif (15 <= $y && $y <= 21){
                            $tmp_end_week = ($x * 4)  - 1;
                        } elseif (22 <= $y && $y <= 31){
                            $tmp_end_week = ($x * 4) ;
                        }

                        for($k = 1; $k <= $tmp_end_week; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);

                    } else {
                        // jika input request tahun diantara tahun start dan tahun end
                        $weeks = [];

                        for($k = 1; $k <= 48; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);
                    }
                }

                // $result[$i]['TIMELINE'][$j] = $weeks;
                array_push($result[$i]['TIMELINE'], $weeks);
            }
        }
        // Test timeline

        return $result;
    }

    public function activity_timeline_old(Request $request)
    {
        $result = [];
        $tahun = $request->tahun;
        $id_outlet = $request->id_outlet;
        $activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        // Detail activity
        for($i = 0; $i < count($activity); $i++){
            $tmp_id_category = $activity[$i]['ID_CATEGORY'];
            $tmp_id_outlet = DB::table('category_activity')->where('ID_CATEGORY', $tmp_id_category)
                                ->value('ID_OUTLET');

            if($tmp_id_outlet == $id_outlet){
                array_push($result, $activity[$i]);
            }
        }

        // Timeplan
        for($i = 0; $i < count($result); $i++){
            for($j = 0; $j < count($timeplan); $j++){
                if($timeplan[$j]['ID_DETAIL_ACTIVITY'] == $result[$i]['ID_DETAIL_ACTIVITY']){
                    $result[$i]['TIMEPLAN'] = $timeplan[$j];
                }
            }
        }

        // Timeline
        for($i = 0; $i < count($result); $i++){
            $tmp_start_date = date('Y', strtotime($result[$i]['TIMEPLAN']['TANGGAL_START']));
            $tmp_end_date = date('Y', strtotime($result[$i]['TIMEPLAN']['TANGGAL_END']));

            $result[$i]['TIMELINE']['COLOR'] = $this->timeline_color(
                $result[$i]['TIMEPLAN']['TANGGAL_START'],
                $result[$i]['TIMEPLAN']['TANGGAL_END'],
                $result[$i]['STATUS']
            );

            // Jika tahun mulai dan tahun selesai sama
            if($tmp_start_date == $tmp_end_date){
                if($tmp_end_date == date('Y', strtotime($tahun))){
                    $tmp_start_month = date('n', strtotime($result[$i]['TIMEPLAN']['TANGGAL_START']));
                    $tmp_end_month = date('n', strtotime($result[$i]['TIMEPLAN']['TANGGAL_END']));
                    $tmp_start_month_date = date('j', strtotime($result[$i]['TIMEPLAN']['TANGGAL_START']));
                    $tmp_end_month_date = date('j', strtotime($result[$i]['TIMEPLAN']['TANGGAL_END']));

                    $a = (int)$tmp_start_month;
                    $b = (int)$tmp_end_month;
                    $x = (int)$tmp_start_month_date;
                    $y = (int)$tmp_end_month_date;
                    $weeks = [];
                    
                    if(1 <= $x && $x <= 7){
                        $tmp_start_week = ($a * 4) - 3;
                    } elseif (8 <= $x && $x <= 14){
                        $tmp_start_week = ($a * 4)  - 2;
                    } elseif (15 <= $x && $x <= 21){
                        $tmp_start_week = ($a * 4)  - 1;
                    } elseif (22 <= $x && $x <= 31){
                        $tmp_start_week = ($a * 4) ;
                    }
                    
                    if(1 <= $y && $y <= 7){
                        $tmp_end_week = ($b * 4) - 3;
                    } elseif (8 <= $y && $y <= 14){
                        $tmp_end_week = ($b * 4)  - 2;
                    } elseif (15 <= $y && $y <= 21){
                        $tmp_end_week = ($b * 4)  - 1;
                    } elseif (22 <= $y && $y <= 31){
                        $tmp_end_week = ($b * 4) ;
                    }

                    for($j = $tmp_start_week; $j <= $tmp_end_week; $j++){
                        array_push($weeks, $j);
                    }

                    $result[$i]['TIMELINE']['WEEKS'] = $weeks;
                } else {
                    $result[$i]['TIMELINE']['WEEKS'] = [];
                }

            } else { // Jika tahun mulai dan tahun selesai beda

                // jika input request tahun = tahun start
                if( $tmp_start_date === date('Y', strtotime($tahun)) ){
                    $tmp_start_month = date('n', strtotime($result[$i]['TIMEPLAN']['TANGGAL_START']));
                    $tmp_start_month_date = date('j', strtotime($result[$i]['TIMEPLAN']['TANGGAL_START']));
                    $x = (int)$tmp_start_month;
                    $y = (int)$tmp_start_month_date;
                    $weeks = [];

                    if(1 <= $y && $y <= 7){
                        $tmp_start_week = ($x * 4) - 3;
                    } elseif (8 <= $y && $y <= 14){
                        $tmp_start_week = ($x * 4)  - 2;
                    } elseif (15 <= $y && $y <= 21){
                        $tmp_start_week = ($x * 4)  - 1;
                    } elseif (22 <= $y && $y <= 31){
                        $tmp_start_week = ($x * 4) ;
                    }

                    for($j = $tmp_start_week; $j <= 48; $j++){
                        array_push($weeks, $j);
                    }

                    $result[$i]['TIMELINE']['WEEKS'] = $weeks;

                } elseif( $tmp_end_date === date('Y', strtotime($tahun)) ){
                    // jika input request tahun = tahun end

                    $tmp_end_month = date('n', strtotime($result[$i]['TIMEPLAN']['TANGGAL_END']));
                    $tmp_end_month_date = date('j', strtotime($result[$i]['TIMEPLAN']['TANGGAL_END']));
                    $x = (int)$tmp_end_month;
                    $y = (int)$tmp_end_month_date;
                    $weeks = [];

                    if(1 <= $y && $y <= 7){
                        $tmp_end_week = ($x * 4) - 3;
                    } elseif (8 <= $y && $y <= 14){
                        $tmp_end_week = ($x * 4)  - 2;
                    } elseif (15 <= $y && $y <= 21){
                        $tmp_end_week = ($x * 4)  - 1;
                    } elseif (22 <= $y && $y <= 31){
                        $tmp_end_week = ($x * 4) ;
                    }

                    for($j = 1; $j <= $tmp_end_week; $j++){
                        array_push($weeks, $j);
                    }

                    $result[$i]['TIMELINE']['WEEKS'] = $weeks;

                } else {
                    // jika input request tahun diantara tahun start dan tahun end
                    $weeks = [];

                    for($j = 1; $j <= 48; $j++){
                        array_push($weeks, $j);
                    }

                    $result[$i]['TIMELINE']['WEEKS'] = $weeks;
                }
            }

        }

        return $result;
    }

    public function timeline_color($start_date, $end_date, $status)
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
                $result = 'black';
            } elseif ($diff_now_to_start->days >= 0 && $diff_now_to_start->invert == 1){
                if($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 0){
                    if($diff_start_to_end->days > 10 && $diff_now_to_end->days <= 10 && $diff_now_to_end->invert == 0){
                        $result = 'yellow';
                    } else {
                        $result = 'lime';
                    }
                } elseif ($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 1){
                    $result = 'red';
                }
            } elseif ($diff_now_to_start->days >= 0 && $diff_now_to_start->invert == 0){
                if($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 0){
                    if($diff_start_to_end->days > 10 && $diff_now_to_end->days <= 10 && $diff_now_to_end->invert == 0){
                        $result = 'yellow';
                    } else {
                        $result = 'lime';
                    }
                } elseif ($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 1){
                    $result = 'red';
                }
            }

        } elseif((int)$status == 1){
            $result = 'blue';
        }

        return $result;
    }

    public function test()
    {
        $result = [];
        $tahun = '2021';
        $id_outlet = '096d71cfc0824fc6919640a4eff6441b';
        $activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        // Detail activity
        for($i = 0; $i < count($activity); $i++){
            $tmp_id_category = $activity[$i]['ID_CATEGORY'];
            $tmp_id_outlet = DB::table('category_activity')->where('ID_CATEGORY', $tmp_id_category)
                                ->value('ID_OUTLET');

            if($tmp_id_outlet == $id_outlet){
                array_push($result, $activity[$i]);
            }
        }

        // Timeplan
        for($i = 0; $i < count($result); $i++){
            $tmp_timeplan = [];

            for($j = 0; $j < count($timeplan); $j++){
                if($timeplan[$j]['ID_DETAIL_ACTIVITY'] == $result[$i]['ID_DETAIL_ACTIVITY']){
                    array_push($tmp_timeplan, $timeplan[$j]);
                }
            }

            $result[$i]['TIMEPLAN'] = $tmp_timeplan;
        }

        // Test timeline
        for($i = 0; $i < count($result); $i++){
            $result[$i]['TIMELINE'] = [];

            for($j = 0; $j < count($result[$i]['TIMEPLAN']); $j++){
                $tmp_start_date = date('Y', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                $tmp_end_date = date('Y', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                
                if($j == count($result[$i]['TIMEPLAN']) - 1){
                    $result[$i]['TIMELINE_COLOR'] = $this->timeline_color(
                        $result[$i]['TIMEPLAN'][$j]['TANGGAL_START'],
                        $result[$i]['TIMEPLAN'][$j]['TANGGAL_END'],
                        $result[$i]['STATUS']
                    );
                }

                // Jika tahun mulai dan tahun selesai sama
                if($tmp_start_date == $tmp_end_date){
                    if($tmp_end_date == date('Y', strtotime($tahun))){
                        $tmp_start_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $tmp_end_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                        $tmp_start_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $tmp_end_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));

                        $a = (int)$tmp_start_month;
                        $b = (int)$tmp_end_month;
                        $x = (int)$tmp_start_month_date;
                        $y = (int)$tmp_end_month_date;
                        $weeks = [];
                        
                        if(1 <= $x && $x <= 7){
                            $tmp_start_week = ($a * 4) - 3;
                        } elseif (8 <= $x && $x <= 14){
                            $tmp_start_week = ($a * 4)  - 2;
                        } elseif (15 <= $x && $x <= 21){
                            $tmp_start_week = ($a * 4)  - 1;
                        } elseif (22 <= $x && $x <= 31){
                            $tmp_start_week = ($a * 4) ;
                        }
                        
                        if(1 <= $y && $y <= 7){
                            $tmp_end_week = ($b * 4) - 3;
                        } elseif (8 <= $y && $y <= 14){
                            $tmp_end_week = ($b * 4)  - 2;
                        } elseif (15 <= $y && $y <= 21){
                            $tmp_end_week = ($b * 4)  - 1;
                        } elseif (22 <= $y && $y <= 31){
                            $tmp_end_week = ($b * 4) ;
                        }

                        for($k = $tmp_start_week; $k <= $tmp_end_week; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);
                        // $result[$i]['TIMELINE'][$j]['WEEKS'] = $weeks;
                    } else {
                        $weeks = [];
                    }

                } else { // Jika tahun mulai dan tahun selesai beda

                    // jika input request tahun = tahun start
                    if( $tmp_start_date === date('Y', strtotime($tahun)) ){
                        $tmp_start_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $tmp_start_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_START']));
                        $x = (int)$tmp_start_month;
                        $y = (int)$tmp_start_month_date;
                        $weeks = [];

                        if(1 <= $y && $y <= 7){
                            $tmp_start_week = ($x * 4) - 3;
                        } elseif (8 <= $y && $y <= 14){
                            $tmp_start_week = ($x * 4)  - 2;
                        } elseif (15 <= $y && $y <= 21){
                            $tmp_start_week = ($x * 4)  - 1;
                        } elseif (22 <= $y && $y <= 31){
                            $tmp_start_week = ($x * 4) ;
                        }

                        for($k = $tmp_start_week; $k <= 48; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);

                    } elseif( $tmp_end_date === date('Y', strtotime($tahun)) ){
                        // jika input request tahun = tahun end

                        $tmp_end_month = date('n', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                        $tmp_end_month_date = date('j', strtotime($result[$i]['TIMEPLAN'][$j]['TANGGAL_END']));
                        $x = (int)$tmp_end_month;
                        $y = (int)$tmp_end_month_date;
                        $weeks = [];

                        if(1 <= $y && $y <= 7){
                            $tmp_end_week = ($x * 4) - 3;
                        } elseif (8 <= $y && $y <= 14){
                            $tmp_end_week = ($x * 4)  - 2;
                        } elseif (15 <= $y && $y <= 21){
                            $tmp_end_week = ($x * 4)  - 1;
                        } elseif (22 <= $y && $y <= 31){
                            $tmp_end_week = ($x * 4) ;
                        }

                        for($k = 1; $k <= $tmp_end_week; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);

                    } else {
                        // jika input request tahun diantara tahun start dan tahun end
                        $weeks = [];

                        for($k = 1; $k <= 48; $k++){
                            array_push($weeks, $k);
                        }

                        // array_push($result[$i]['TIMELINE'], $weeks);
                    }
                }

                // $result[$i]['TIMELINE'][$j] = $weeks;
                array_push($result[$i]['TIMELINE'], $weeks);
            }
        }
        // Test timeline

        dd($result);
    }
}