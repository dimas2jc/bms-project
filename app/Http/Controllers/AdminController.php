<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use App\Models\CategoryActivity;
use App\Models\Outlet;

use Carbon\Carbon;

class AdminController extends Controller
{
    public function timetable()
    {

        return view('admin.index');
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
}
