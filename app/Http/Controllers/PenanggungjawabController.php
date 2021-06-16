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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;

class PenanggungjawabController extends Controller
{
    public function timetable()
    {
        $outlet = Outlet::all()->toArray();
        $category_activity = CategoryActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $pic = User::where('ROLE', 2)->get()->toArray();
        $detail_activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        //mengambil log
        $id = Auth::user()->ID_USER;
        $log = UserLog::where('user', '=', $id)->get()->toArray();

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

        return view('pic.timetable', compact('category_activity', 'detail_activity', 'outlet', 'pic', 'timeplan', 'log'));
    }

    public function progress()
    {
        $pic = Auth::user()->ID_USER;
        $log = UserLog::where('user', '=', $pic)->get();
        // $activity = Progress::join('detail_activity as d', 'd.ID_DETAIL_ACTIVITY', '=', 'progress.ID_DETAIL_ACTIVITY')->get();
        $activity = DetailActivity::all();
        $progress = Progress::all();

        $data = [];
        $detail = [];

        foreach($log as $l){
            foreach($activity as $a){
                if($a->ID_DETAIL_ACTIVITY == $l->activity){
                    $total = 0;

                    foreach($progress as $p){
                        if($p->ID_DETAIL_ACTIVITY == $a->ID_DETAIL_ACTIVITY){
                            $detail[] = $p;
                            $total = $total + $p->PROGRESS;
                        }
                    }
                    $a['TOTAL_PROGRESS'] = $total;
                    $data[] = $a;
                }
            }
        }
        // dd($data);

        return view('pic.progress', compact('data'));
    }

    public function update_progress(Request $request)
    {
        $nama_file = null;

        if($request->file('file') != null)
        {
            $file = $request->file('file');
            $nama_file = $file->getClientOriginalName();
            $file->move('assets/dokumen/', $nama_file);
        }

        Progress::insert([
            'ID_PROGRESS' => Uuid::uuid4()->getHex(),
            'ID_DETAIL_ACTIVITY' => $request->id_detail_activity,
            'PROGRESS' => $request->progress,
            'KETERANGAN' => $request->keterangan,
            'FILE' => $nama_file,
            'created_at' => Carbon::now()
        ]);

        // Cek progress
        $progress = Progress::where('ID_DETAIL_ACTIVITY', '=', $request->id_detail_activity)->sum('PROGRESS');

        // Jika progress 100%
        if($progress == "100"){
            DetailActivity::where('ID_DETAIL_ACTIVITY', '=', $request->id_detail_activity)->update([
                'STATUS' => 1,
                'updated_at' => Carbon::now()
            ]);
        }

        return redirect('/pic/progress');
    }

    public function detail_progress($id)
    {
        $data = Progress::where('ID_DETAIL_ACTIVITY', '=', $id)->get();
        $activity = DetailActivity::select('NAMA_AKTIFITAS')->where('ID_DETAIL_ACTIVITY', '=', $id)->first();

        return view('pic.detail-progress', compact('data', 'activity'));
    }

    public function download_file(Request $request)
    {
        $data = Progress::where('ID_PROGRESS', '=', $request->id)->first();
        $file = public_path()."/assets/dokumen/".$data->FILE;

        return response()->download($file, $data->FILE);
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
                    $result = 3;
                }
            } elseif ($diff_now_to_start->days >= 0 && $diff_now_to_start->invert == 0){
                if($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 0){
                    if($diff_start_to_end->days > 10 && $diff_now_to_end->days <= 10 && $diff_now_to_end->invert == 0){
                        $result = 1;
                    } else {
                        $result = 1;
                    }
                } elseif ($diff_now_to_end->days >= 0 && $diff_now_to_end->invert == 1){
                    $result = 3;
                }
            }

        } elseif((int)$status == 1){
            $result = 4;
        }

        return $result;
    }
}
