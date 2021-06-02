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
    
    public function update(Request $request)
    {
        dd($request->all());
    }
}