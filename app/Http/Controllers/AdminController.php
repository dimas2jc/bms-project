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
use App\Models\CategoryCalendar;
use App\Models\Calendar;
use App\Models\DetailCategoryCalendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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

                $progress = DB::table('progress')->where('ID_DETAIL_ACTIVITY', $detail_activity[$i]['ID_DETAIL_ACTIVITY'])->first();
                $detail_activity[$i]['ID_PROGRESS'] = $progress->ID_PROGRESS;
                $detail_activity[$i]['PROGRESS'] = $progress->PROGRESS;
                $detail_activity[$i]['KETERANGAN'] = $progress->KETERANGAN;
                $detail_activity[$i]['FILE'] = $progress->FILE;

            } else {
                $detail_activity[$i]['PROGRESS'] = 0;
            }
        }

        return view('admin.index', compact('category_activity', 'detail_activity', 'outlet', 'pic', 'timeplan'));
    }

    public function userPIC()
    {
        $data = User::where('ROLE', '=', "2")->get();

        return view('admin.pic', compact('data'));
    }

    public function storePIC(Request $request)
    {
        User::insert([
            'ID_USER' => Uuid::uuid4()->getHex(),
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'NAMA' => $request->nama,
            'EMAIL' => $request->email,
            'NO_TELP' => $request->no_telp,
            'ROLE' => 2,
            'created_at' => Carbon::now()
        ]);

        return redirect('admin/user-pic');
    }

    public function editPIC(Request $request)
    {
        User::findOrFail($request->id)->update([
            'username' => $request->username,
            'NAMA' => $request->nama,
            'EMAIL' => $request->email,
            'NO_TELP' => $request->no_telp,
            'updated_at' => Carbon::now()
        ]);

        return redirect('admin/user-pic');
    }

    public function resetPassPIC(Request $request)
    {
        $username = User::select('username')->where('ID_USER', '=', $request->id)->first();
        User::findOrFail($request->id)->update(['password' => Hash::make($username->username), 'updated_at' => Carbon::now()]);

        return redirect('admin/user-pic');
    }

    public function userInvestor()
    {
        $data = User::where('ROLE', '=', "3")->get();
        $outlet = Outlet::all();

        return view('admin.investor', compact('data', 'outlet'));
    }

    public function storeInvestor(Request $request)
    {
        User::insert([
            'ID_USER' => Uuid::uuid4()->getHex(),
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'NAMA' => $request->nama,
            'EMAIL' => $request->email,
            'NO_TELP' => $request->no_telp,
            'ROLE' => 3,
            'created_at' => Carbon::now()
        ]);

        $id = User::orderBy('created_at', 'DESC')->first();

        UserLog::insert([
            'id' => Uuid::uuid4()->getHex(),
            'user' => $id->ID_USER,
            'outlet' => $request->outlet,
            'created_at' => Carbon::now()
        ]);

        return redirect('admin/user-investor');
    }

    public function editInvestor(Request $request)
    {
        User::findOrFail($request->id)->update([
            'username' => $request->username,
            'NAMA' => $request->nama,
            'EMAIL' => $request->email,
            'NO_TELP' => $request->no_telp,
            'updated_at' => Carbon::now()
        ]);

        return redirect('admin/user-investor');
    }

    public function resetPassInvestor(Request $request)
    {
        $username = User::select('username')->where('ID_USER', '=', $request->id)->first();
        User::findOrFail($request->id)->update(['password' => Hash::make($username->username), 'updated_at' => Carbon::now()]);

        return redirect('admin/user-investor');
    }

    public function category()
    {
        $data = CategoryActivity::select('category_activity.ID_CATEGORY','category_activity.NAMA as CATEGORY', 'o.NAMA as OUTLET')
                ->join('outlet as o', 'o.ID_OUTLET', '=', 'category_activity.ID_OUTLET')
                ->get();
        $outlet = Outlet::all();

        return view('admin.category', compact('data', 'outlet'));
    }

    public function storeCategory(Request $request)
    {
        CategoryActivity::insert([
            'ID_CATEGORY' => Uuid::uuid4()->getHex(),
            'ID_OUTLET' => $request->outlet,
            'NAMA' => $request->nama,
            'created_at' => Carbon::now()
        ]);

        return redirect('admin/category-timetable');
    }

    public function editCategory(Request $request)
    {
        CategoryActivity::findOrFail($request->id)->update(['NAMA' => $request->nama, 'updated_at' => Carbon::now()]);

        return redirect('admin/category-timetable');
    }

    public function category_calendar()
    {
        $data = CategoryCalendar::all();

        return view('admin.category-calendar', compact('data'));
    }

    public function storeCategoryCalendar(Request $request)
    {
        CategoryCalendar::insert([
            'ID_CATEGORY_CALENDAR' => Uuid::uuid4()->getHex(),
            'NAMA' => $request->nama,
            'created_at' => Carbon::now()
        ]);

        return redirect('admin/category-calendar');
    }

    public function editCategoryCalendar(Request $request)
    {
        CategoryCalendar::findOrFail($request->id)->update(['NAMA' => $request->nama, 'updated_at' => Carbon::now()]);

        return redirect('admin/category-calendar');
    }

    public function calendar()
    {
        $outlet = Outlet::all()->toArray();
        $data = null;

        return view('admin.calendar', compact('data', 'outlet'));
    }

    public function index_tambah_calendar()
    {
        $outlet = Outlet::all();
        $category = CategoryCalendar::all();

        return view('admin.tambah-calendar', compact('outlet', 'category'));
    }

    public function storeCalendar(Request $request)
    {
        Calendar::insert([
            'ID_CALENDAR' => Uuid::uuid4()->getHex(),
            'ID_CATEGORY_CALENDAR' => $request->category,
            'JUDUL' => $request->judul,
            'DESKRIPSI' => $request->deskripsi,
            'TANGGAL_START' => $request->date_start,
            'TANGGAL_END' => $request->date_end,
            'created_at' => Carbon::now()
        ]);

        for($i = 0; $i < count($request->outlet); $i++){
            DetailCategoryCalendar::insert([
                'ID_OUTLET' => $request->outlet[$i],
                'ID_CATEGORY_CALENDAR' => $request->category,
                'created_at' => Carbon::now()
            ]);
        };

        return redirect('admin/calendar');
    }

    public function updateCalendar(Request $request)
    {
        Calendar::where('ID_CALENDAR', '=', $request->id)->update([
            'JUDUL' => $request->judul,
            'DESKRIPSI' => $request->deskripsi,
            'TANGGAL_START' => $request->date_start,
            'TANGGAL_END' => $request->date_end,
            'updated_at' => Carbon::now()
        ]);

        return redirect('admin/calendar');
    }

    public function getCalendar($outlet)
    {
        $outlet = Outlet::all()->toArray();
        $detail_category = DetailCategoryCalendar::where('ID_OUTLET', '=', $outlet)->get()->toArray();
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

        return view('admin.calendar', compact('data', 'category', 'outlet'));
    }

    public function getDetailCalendar($id)
    {
        $data = Calendar::where('ID_CALENDAR', '=', $id)->first();

        return view('admin.detail-calendar', compact('data'));
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
