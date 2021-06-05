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

        return view('admin.investor', compact('data'));
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

        return redirect('admin/category');
    }

    public function editCategory(Request $request)
    {
        CategoryActivity::findOrFail($request->id)->update(['NAMA' => $request->nama, 'updated_at' => Carbon::now()]);

        return redirect('admin/category');
    }

    public function calendar()
    {
        $outlet = Outlet::all()->toArray();
        $category_activity = null;
        $detail_activity = null;
        $timeplan = null;

        return view('admin.calendar', compact('category_activity', 'detail_activity', 'outlet', 'timeplan'));
    }

    public function updateCalendar(Request $request)
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
            'ID_DETAIL_ACTIVITY' => $request->id,
            'KETERANGAN' => $request->keterangan,
            'FILE' => $nama_file
        ]);

        return redirect('admin/calendar/'.$request->category);
    }

    public function getCategory($outlet)
    {
        $data = CategoryActivity::where('ID_OUTLET', '=', $outlet)->get();

        return response()->json(["success" => true, "data" => $data]);
    }

    public function getCalendar($id_category)
    {
        $outlet = Outlet::all()->toArray();
        $category_activity = CategoryActivity::orderBy('created_at', 'ASC')->first()->toArray();
        $detail_activity = DetailActivity::where('ID_CATEGORY', '=', $id_category)->orderBy('created_at', 'ASC')->get()->toArray();
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

        return view('admin.calendar', compact('category_activity', 'detail_activity', 'outlet', 'timeplan'));
    }
}
