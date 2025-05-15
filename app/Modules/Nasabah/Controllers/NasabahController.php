<?php
namespace App\Modules\Nasabah\Controllers;

use App\Helpers\Logger;
use App\Http\Controllers\Controller;
use App\Modules\BankGaji\Models\BankGaji;
use App\Modules\Dinas\Models\Dinas;
use App\Modules\Kunjungan\Models\Kunjungan;
use App\Modules\Log\Models\Log;
use App\Modules\Nasabah\Models\Nasabah;
use App\Modules\Unit\Models\Unit;
use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NasabahController extends Controller
{
    use Logger;
    protected $log;
    protected $title = "Nasabah";

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function index(Request $request)
    {
        // dd(session()->get('active_role')['role']);
        $data['filter'] = [
            '' => "-PILIH SALAH SATU-",
            'pensiun' => 'Pensiun',
            'aktif' => 'Aktif',
            'pensiun_tahun_ini' => 'Pensiun Tahun Ini',
            'pensiun_bulan_ini' => 'Pensiun Bulan Ini',
            'pensiun_bulan_depan' => 'Pensiun Bulan Depan',
            'belum_dikunjungi' => 'Nasabah Belum Dikunjungi',
        ];
        $data['filter_aktif'] = $request->input('filter');
        $data['dinas_aktif'] = $request->input('id_dinas');
        $data['dinas'] = Dinas::all()->pluck('nama_dinas', 'id');
        // $dinas->pre
        $data['dinas']->prepend('-PILIH SALAH SATU-', '');

        $thn_pensiun = date("Y") - 60;
        $bln_pensiun = date("m");
        $bln_depan = date('m', strtotime('first day of +1 month'));

        $query = Nasabah::query()->join('unit', 'nasabah.id_unit', '=', 'unit.id');
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nama_nasabah', 'like', "%$search%");
        }

        if ($request->has('filter')) {
            $id_dinas = $request->get('id_dinas');
            $query->where('id_dinas', $id_dinas);
        }

        if ($request->has('filter')) {
            $filter = $request->get('filter');

            if ($filter == 'pensiun') {
                $query->where('is_pensiun', '1');
            } else if ($filter == 'aktif') {
                $query->where('is_pensiun', '0');
            } else if ($filter == 'pensiun_tahun_ini') {
                $query->where('tgl_lahir', 'like', "$thn_pensiun%");
            } else if ($filter == 'pensiun_bulan_ini') {
                $query->where('tgl_lahir', 'like', "$thn_pensiun-$bln_pensiun-%");
            } else if ($filter == 'pensiun_bulan_depan') {
                $query->where('tgl_lahir', 'like', "$thn_pensiun-$bln_depan-%");
            } else if ($filter == 'belum_dikunjungi') {
                $id_nasabah = Kunjungan::groupBy('id_nasabah')->pluck('id_nasabah')->all();
                $query->whereNotIn('id', $id_nasabah);
                // dd($query);
            }
            // else {
            //     if ($filter == 'belum_dikunjungi') {
            //         $data['data'] = Nasabah::get_nasabah_blm_kunjungan()->paginate(10)->withQueryString();
            //     } else {
            //         $data['data'] = $query->paginate(10)->withQueryString();
            //     }
            // }
        }

        $data['data'] = $query->paginate(10)->withQueryString();

        $this->log($request, 'melihat halaman manajemen data ' . $this->title);
        return view('Nasabah::nasabah', array_merge($data, ['title' => $this->title]));
    }

    public function create(Request $request)
    {
        $ref_bank_gaji = BankGaji::all()->pluck('nama_bank', 'id');
        $ref_bank_gaji->prepend('-PILIH SALAH SATU-', '');
        $ref_unit = Unit::all()->pluck('nama_unit', 'id');
        $ref_unit->prepend('-PILIH SALAH SATU-', '');

        $data['forms'] = [
            'nama_nasabah' => ['Nama Nasabah', Form::text("nama_nasabah", old("nama_nasabah"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'cif' => ['Cif', Form::text("cif", old("cif"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'id_bank_gaji' => ['Bank Gaji', Form::select("id_bank_gaji", $ref_bank_gaji, null, ["class" => "form-control select2"])],
            'id_unit' => ['Unit', Form::select("id_unit", $ref_unit, null, ["class" => "form-control select2"])],
            'nip' => ['Nip', Form::text("nip", old("nip"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'tempat_lahir' => ['Tempat Lahir', Form::text("tempat_lahir", old("tempat_lahir"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'tgl_lahir' => ['Tgl Lahir', Form::date("tgl_lahir", old("tgl_lahir"), ["class" => "form-control datepicker", "required" => "required"])],
            'alamat' => ['Alamat', Form::textarea("alamat", old("alamat"), ["class" => "form-control rich-editor"])],
            'keterangan' => ['Keterangan', Form::textarea("keterangan", old("keterangan"), ["class" => "form-control rich-editor"])],

        ];

        $this->log($request, 'membuka form tambah ' . $this->title);
        return view('Nasabah::nasabah_create', array_merge($data, ['title' => $this->title]));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'alamat' => 'required',
            'cif' => 'required',
            'id_bank_gaji' => 'required',
            'id_unit' => 'required',
            'keterangan' => 'required',
            'nama_nasabah' => 'required',
            'nip' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',

        ]);

        $nasabah = new Nasabah();
        $nasabah->alamat = $request->input("alamat");
        $nasabah->cif = $request->input("cif");
        $nasabah->id_bank_gaji = $request->input("id_bank_gaji");
        $nasabah->id_unit = $request->input("id_unit");
        $nasabah->keterangan = $request->input("keterangan");
        $nasabah->nama_nasabah = $request->input("nama_nasabah");
        $nasabah->nip = $request->input("nip");
        $nasabah->tempat_lahir = $request->input("tempat_lahir");
        $nasabah->tgl_lahir = $request->input("tgl_lahir");

        $nasabah->created_by = Auth::id();
        $nasabah->save();

        $text = 'membuat ' . $this->title; //' baru '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return redirect()->route('nasabah.index')->with('message_success', 'Nasabah berhasil ditambahkan!');
    }

    public function show(Request $request, Nasabah $nasabah)
    {
        $data['nasabah'] = $nasabah;
        $data['kunjungan'] = Kunjungan::where('id_nasabah', $nasabah->id)->get();

        $text = 'melihat detail ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return view('Nasabah::nasabah_detail', array_merge($data, ['title' => $this->title]));
    }

    public function edit(Request $request, Nasabah $nasabah)
    {
        $data['nasabah'] = $nasabah;

        $ref_bank_gaji = BankGaji::all()->pluck('nama_bank', 'id');
        $ref_unit = Unit::all()->pluck('nama_unit', 'id');

        $ref_status_kepegawaian = [
            '' => '-PILIH SALAH SATU-',
            0 => 'Belum Pensiun',
            1 => 'Pensiun',
        ];

        $data['forms'] = [
            'nama_nasabah' => ['Nama Nasabah', Form::text("nama_nasabah", $nasabah->nama_nasabah, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "nama_nasabah"])],
            'alamat' => ['Alamat', Form::textarea("alamat", $nasabah->alamat, ["class" => "form-control rich-editor"])],
            'is_pensiun' => ['Status Kepegawaian', Form::select("is_pensiun", $ref_status_kepegawaian, $nasabah->is_pensiun, ["class" => "form-control select2", "required" => "required", "id" => "tgl_lahir"])],
            'cif' => ['Cif', Form::text("cif", $nasabah->cif, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "cif"])],
            'id_bank_gaji' => ['Bank Gaji', Form::select("id_bank_gaji", $ref_bank_gaji, null, ["class" => "form-control select2"])],
            'id_unit' => ['Unit', Form::select("id_unit", $ref_unit, null, ["class" => "form-control select2"])],
            'keterangan' => ['Keterangan', Form::textarea("keterangan", $nasabah->keterangan, ["class" => "form-control rich-editor"])],
            'nip' => ['Nip', Form::text("nip", $nasabah->nip, ["class" => "form-control", "placeholder" => "", "id" => "nip"])],
            'tempat_lahir' => ['Tempat Lahir', Form::text("tempat_lahir", $nasabah->tempat_lahir, ["class" => "form-control", "placeholder" => "", "id" => "tempat_lahir"])],
            'tgl_lahir' => ['Tgl Lahir', Form::date("tgl_lahir", $nasabah->tgl_lahir, ["class" => "form-control", "id" => "tgl_lahir"])],

        ];

        $text = 'membuka form edit ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return view('Nasabah::nasabah_update', array_merge($data, ['title' => $this->title]));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'alamat' => 'required',
            'cif' => 'required',
            // 'id_bank_gaji' => 'required',
            // 'id_unit' => 'required',
            // 'keterangan' => 'required',
            'nama_nasabah' => 'required',
            // 'nip' => 'required',
            // 'tempat_lahir' => 'required',
            // 'tgl_lahir' => 'required',
            'is_pensiun' => 'required',

        ]);

        $nasabah = Nasabah::find($id);
        $nasabah->alamat = $request->input("alamat");
        $nasabah->cif = $request->input("cif");
        $nasabah->id_bank_gaji = $request->input("id_bank_gaji");
        $nasabah->id_unit = $request->input("id_unit");
        $nasabah->keterangan = $request->input("keterangan");
        $nasabah->nama_nasabah = $request->input("nama_nasabah");
        $nasabah->nip = $request->input("nip");
        $nasabah->tempat_lahir = $request->input("tempat_lahir");
        $nasabah->tgl_lahir = $request->input("tgl_lahir");
        $nasabah->is_pensiun = $request->input("is_pensiun");

        $nasabah->updated_by = Auth::id();
        $nasabah->save();

        $text = 'mengedit ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return redirect()->route('nasabah.index')->with('message_success', 'Nasabah berhasil diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $nasabah = Nasabah::find($id);
        $nasabah->deleted_by = Auth::id();
        $nasabah->save();
        $nasabah->delete();

        $text = 'menghapus ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return back()->with('message_success', 'Nasabah berhasil dihapus!');
    }
}
