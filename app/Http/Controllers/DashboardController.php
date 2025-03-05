<?php

namespace App\Http\Controllers;

use App\Helpers\Permission;
use App\Modules\Kunjungan\Models\Kunjungan;
use App\Modules\Nasabah\Models\Nasabah;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['nasabah'] = Nasabah::all();
        $data['user'] = Users::all();
        $data['kunjungan'] = Kunjungan::all();
        $data['kunjungan_invalid'] = Kunjungan::join('status_kunjungan as s', 'kunjungan.id_status_kunjungan', '=', 's.id')->where('s.status_kunjungan', 'Belum Valid')->get();
        $thn_pensiun = date("Y") - 60;
        $bln_pensiun = date("m");
        $bln_depan = date('m', strtotime('first day of +1 month'));
        $data['pensiun_tahun_ini'] = Nasabah::where('tgl_lahir', 'like', "$thn_pensiun%")->get();
        $data['pensiun_bulan_ini'] = Nasabah::where('tgl_lahir', 'like', "$thn_pensiun-$bln_pensiun-%")->get();
        $data['pensiun_bulan_depan'] = Nasabah::where('tgl_lahir', 'like', "$thn_pensiun-$bln_depan-%")->get();

        return view('dashboard', $data);
    }

    public function changeRole($id_role)
    {
        $user = Auth::user();

        // get user's role
        $roles = Permission::getRole($user->id);
        if ($roles->count() == 0)
            abort(403);
        $active_role = $roles->where('id', $id_role)->first()->only(['id', 'role']);
        // dd($active_role);
        // get user's menu
        $menus = Permission::getMenu($active_role);

        // get user's privilege
        $privileges = Permission::getPrivilege($active_role);
        $privileges = $privileges->mapWithKeys(function ($item, $key) {
            return [$item['module'] => $item->only(['create', 'read', 'update', 'delete', 'show_menu', 'show'])];
        });

        // store to session
        session(['menus' => $menus]);
        session(['roles' => $roles->pluck('role', 'id')->all()]);
        session(['privileges' => $privileges->all()]);
        session(['active_role' => $active_role]);

        return redirect()->route('dashboard')->with('message_success', 'Berhasil memperbarui role/session sebagai ' . $active_role['role']);
    }

    public function forceLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
