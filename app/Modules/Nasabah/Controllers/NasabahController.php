<?php

namespace App\Modules\Nasabah\Controllers;

use App\Helpers\Logger;
use App\Http\Controllers\Controller;
use App\Modules\BankGaji\Models\BankGaji;
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
        $query = Nasabah::query();
        if ($request->has('search')) {
            $search = $request->get('search');
            // $query->where('name', 'like', "%$search%");
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
            'cif'          => ['Cif', Form::text("cif", old("cif"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'id_bank_gaji' => ['Bank Gaji', Form::select("id_bank_gaji", $ref_bank_gaji, null, ["class" => "form-control select2"])],
            'id_unit'      => ['Unit', Form::select("id_unit", $ref_unit, null, ["class" => "form-control select2"])],
            'nip'          => ['Nip', Form::text("nip", old("nip"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'tempat_lahir' => ['Tempat Lahir', Form::text("tempat_lahir", old("tempat_lahir"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'tgl_lahir'    => ['Tgl Lahir', Form::date("tgl_lahir", old("tgl_lahir"), ["class" => "form-control datepicker", "required" => "required"])],
            'alamat'       => ['Alamat', Form::textarea("alamat", old("alamat"), ["class" => "form-control rich-editor"])],
            'keterangan'   => ['Keterangan', Form::textarea("keterangan", old("keterangan"), ["class" => "form-control rich-editor"])],

        ];

        $this->log($request, 'membuka form tambah ' . $this->title);
        return view('Nasabah::nasabah_create', array_merge($data, ['title' => $this->title]));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'alamat'       => 'required',
            'cif'          => 'required',
            'id_bank_gaji' => 'required',
            'id_unit'      => 'required',
            'keterangan'   => 'required',
            'nama_nasabah' => 'required',
            'nip'          => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir'    => 'required',

        ]);

        $nasabah               = new Nasabah();
        $nasabah->alamat       = $request->input("alamat");
        $nasabah->cif          = $request->input("cif");
        $nasabah->id_bank_gaji = $request->input("id_bank_gaji");
        $nasabah->id_unit      = $request->input("id_unit");
        $nasabah->keterangan   = $request->input("keterangan");
        $nasabah->nama_nasabah = $request->input("nama_nasabah");
        $nasabah->nip          = $request->input("nip");
        $nasabah->tempat_lahir = $request->input("tempat_lahir");
        $nasabah->tgl_lahir    = $request->input("tgl_lahir");

        $nasabah->created_by = Auth::id();
        $nasabah->save();

        $text = 'membuat ' . $this->title; //' baru '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return redirect()->route('nasabah.index')->with('message_success', 'Nasabah berhasil ditambahkan!');
    }

    public function show(Request $request, Nasabah $nasabah)
    {
        $data['nasabah'] = $nasabah;

        $text = 'melihat detail ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return view('Nasabah::nasabah_detail', array_merge($data, ['title' => $this->title]));
    }

    public function edit(Request $request, Nasabah $nasabah)
    {
        $data['nasabah'] = $nasabah;

        $ref_bank_gaji = BankGaji::all()->pluck('created_by', 'id');
        $ref_unit      = Unit::all()->pluck('created_by', 'id');

        $data['forms'] = [
            'alamat'       => ['Alamat', Form::textarea("alamat", $nasabah->alamat, ["class" => "form-control rich-editor"])],
            'cif'          => ['Cif', Form::text("cif", $nasabah->cif, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "cif"])],
            'id_bank_gaji' => ['Bank Gaji', Form::select("id_bank_gaji", $ref_bank_gaji, null, ["class" => "form-control select2"])],
            'id_unit'      => ['Unit', Form::select("id_unit", $ref_unit, null, ["class" => "form-control select2"])],
            'keterangan'   => ['Keterangan', Form::textarea("keterangan", $nasabah->keterangan, ["class" => "form-control rich-editor"])],
            'nama_nasabah' => ['Nama Nasabah', Form::text("nama_nasabah", $nasabah->nama_nasabah, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "nama_nasabah"])],
            'nip'          => ['Nip', Form::text("nip", $nasabah->nip, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "nip"])],
            'tempat_lahir' => ['Tempat Lahir', Form::text("tempat_lahir", $nasabah->tempat_lahir, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "tempat_lahir"])],
            'tgl_lahir'    => ['Tgl Lahir', Form::text("tgl_lahir", $nasabah->tgl_lahir, ["class" => "form-control datepicker", "required" => "required", "id" => "tgl_lahir"])],

        ];

        $text = 'membuka form edit ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return view('Nasabah::nasabah_update', array_merge($data, ['title' => $this->title]));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'alamat'       => 'required',
            'cif'          => 'required',
            'id_bank_gaji' => 'required',
            'id_unit'      => 'required',
            'keterangan'   => 'required',
            'nama_nasabah' => 'required',
            'nip'          => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir'    => 'required',

        ]);

        $nasabah               = Nasabah::find($id);
        $nasabah->alamat       = $request->input("alamat");
        $nasabah->cif          = $request->input("cif");
        $nasabah->id_bank_gaji = $request->input("id_bank_gaji");
        $nasabah->id_unit      = $request->input("id_unit");
        $nasabah->keterangan   = $request->input("keterangan");
        $nasabah->nama_nasabah = $request->input("nama_nasabah");
        $nasabah->nip          = $request->input("nip");
        $nasabah->tempat_lahir = $request->input("tempat_lahir");
        $nasabah->tgl_lahir    = $request->input("tgl_lahir");

        $nasabah->updated_by = Auth::id();
        $nasabah->save();

        $text = 'mengedit ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return redirect()->route('nasabah.index')->with('message_success', 'Nasabah berhasil diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $nasabah             = Nasabah::find($id);
        $nasabah->deleted_by = Auth::id();
        $nasabah->save();
        $nasabah->delete();

        $text = 'menghapus ' . $this->title; //.' '.$nasabah->what;
        $this->log($request, $text, ['nasabah.id' => $nasabah->id]);
        return back()->with('message_success', 'Nasabah berhasil dihapus!');
    }
}
