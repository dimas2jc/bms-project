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
use Carbon\Carbon;

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

                    $detail_activity[$i]['TANGGAL_START'] = $tanggal_mulai;
                    $detail_activity[$i]['TANGGAL_END'] = $tanggal_selesai;
                    $detail_activity[$i]['DURASI'] = $durasi;
                }
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
        $category_activity = CategoryActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $detail_activity = DetailActivity::orderBy('created_at', 'ASC')->get()->toArray();
        $timeplan = Timeplan::orderBy('created_at', 'ASC')->get()->toArray();

        for($i = 0; $i < count($detail_activity); $i++){
            for($j = 0; $j < count($timeplan); $j++){
                if($timeplan[$j]['ID_DETAIL_ACTIVITY'] == $detail_activity[$i]['ID_DETAIL_ACTIVITY']){
                    $tanggal_mulai = $timeplan[$j]['TANGGAL_START'];
                    $tanggal_selesai = $timeplan[$j]['TANGGAL_END'];
                    $durasi = date_diff(date_create($tanggal_mulai), date_create($tanggal_selesai));

                    $detail_activity[$i]['TANGGAL_START'] = $tanggal_mulai;
                    $detail_activity[$i]['TANGGAL_END'] = $tanggal_selesai;
                    $detail_activity[$i]['DURASI'] = $durasi;
                }
            }
        }

        return view('admin.calendar', compact('category_activity', 'detail_activity', 'outlet', 'timeplan'));
    }
}
