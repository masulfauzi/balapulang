<?php
namespace App\Modules\Kunjungan\Controllers;

use App\Helpers\Logger;
use App\Http\Controllers\Controller;
use App\Modules\Kunjungan\Models\Kunjungan;
use App\Modules\Log\Models\Log;
use App\Modules\Nasabah\Models\Nasabah;
use App\Modules\StatusKunjungan\Models\StatusKunjungan;
use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KunjunganController extends Controller
{
    use Logger;
    protected $log;
    protected $title = "Kunjungan";

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function index(Request $request)
    {
        $query = Kunjungan::query();
        if ($request->has('search')) {
            $search = $request->get('search');
            // $query->where('name', 'like', "%$search%");
        }
        $data['data'] = $query->paginate(10)->withQueryString();

        $this->log($request, 'melihat halaman manajemen data ' . $this->title);
        return view('Kunjungan::kunjungan', array_merge($data, ['title' => $this->title]));
    }

    public function create(Request $request)
    {
        $ref_nasabah = Nasabah::all()->pluck('nama_nasabah', 'id');
        $ref_nasabah->prepend('PILIH SALAH SATU-', '');
        // $ref_status_kunjungan = StatusKunjungan::all()->pluck('created_by', 'id');
        // $ref_nasabah          = Nasabah::all()->pluck('cif', 'id');

        if ($request->id_nasabah == null) {
            $id_nasabah = '';
        } else {
            $id_nasabah = $request->id_nasabah;
        }

        $data['forms'] = [
            'id_nasabah'      => ['Nasabah', Form::select("id_nasabah", $ref_nasabah, $id_nasabah, ["class" => "form-control select2"])],
            'hasil_kunjungan' => ['Hasil Kunjungan', Form::textarea("hasil_kunjungan", old("hasil_kunjungan"), ["class" => "form-control rich-editor"])],
            'foto'            => ['Foto', Form::file("foto", ["class" => "form-control", "accept" => "capture=camera,image/*"])],
            // 'id_user'         => ['User', Form::select("id_user", $ref_nasabah, null, ["class" => "form-control select2"])],
            'tgl_kunjungan'   => ['Tgl Kunjungan', Form::text("tgl_kunjungan", old("tgl_kunjungan"), ["class" => "form-control datepicker", "required" => "required"])],

        ];

        $this->log($request, 'membuka form tambah ' . $this->title);
        return view('Kunjungan::kunjungan_create', array_merge($data, ['title' => $this->title]));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'hasil_kunjungan' => 'required',
            'id_nasabah'      => 'required',
            'foto'            => 'required|mimes:jpg,jpeg,png,PNG,JPG,JPEG|max:10240',
            // 'foto.*' => 'required|mimes:jpg,jpeg,png,PNG,JPG,JPEG|max:10240',
            // 'id_user'             => 'required',
            'tgl_kunjungan'   => 'required',

        ]);

        $fileName = time() . '.' . $request->foto->extension();

        $request->foto->move(public_path('uploads/'), $fileName);

        $status_kunjungan = StatusKunjungan::where('kode_kunjungan', '0')->first();

        $kunjungan                      = new Kunjungan();
        $kunjungan->hasil_kunjungan     = $request->input("hasil_kunjungan");
        $kunjungan->id_nasabah          = $request->input("id_nasabah");
        $kunjungan->id_status_kunjungan = $status_kunjungan->id;
        $kunjungan->foto                = $fileName;
        $kunjungan->id_user             = Auth::user()->id;
        $kunjungan->tgl_kunjungan       = $request->input("tgl_kunjungan");

        $kunjungan->created_by = Auth::id();
        $kunjungan->save();

        $text = 'membuat ' . $this->title; //' baru '.$kunjungan->what;
        $this->log($request, $text, ['kunjungan.id' => $kunjungan->id]);
        return redirect()->route('kunjungan.index')->with('message_success', 'Kunjungan berhasil ditambahkan!');
    }

    public function show(Request $request, Kunjungan $kunjungan)
    {
        $data['kunjungan'] = $kunjungan;

        $text = 'melihat detail ' . $this->title; //.' '.$kunjungan->what;
        $this->log($request, $text, ['kunjungan.id' => $kunjungan->id]);
        return view('Kunjungan::kunjungan_detail', array_merge($data, ['title' => $this->title]));
    }

    public function validasi(Request $request, Kunjungan $kunjungan)
    {
        $status_kunjungan = StatusKunjungan::where('status_kunjungan', 'Valid')->first();

        $kunjungan->id_status_kunjungan = $status_kunjungan->id;
        $kunjungan->save();

        return redirect()->back()->with('message_sukses', 'Kunjungan telah divalidasi');

    }

    public function edit(Request $request, Kunjungan $kunjungan)
    {
        $data['kunjungan'] = $kunjungan;

        $ref_nasabah          = Nasabah::all()->pluck('cif', 'id');
        $ref_status_kunjungan = StatusKunjungan::all()->pluck('created_by', 'id');
        $ref_nasabah          = Nasabah::all()->pluck('cif', 'id');

        $data['forms'] = [
            'hasil_kunjungan'     => ['Hasil Kunjungan', Form::textarea("hasil_kunjungan", $kunjungan->hasil_kunjungan, ["class" => "form-control rich-editor"])],
            'id_nasabah'          => ['Nasabah', Form::select("id_nasabah", $ref_nasabah, null, ["class" => "form-control select2"])],
            'id_status_kunjungan' => ['Status Kunjungan', Form::select("id_status_kunjungan", $ref_status_kunjungan, null, ["class" => "form-control select2"])],
            'id_user'             => ['User', Form::select("id_user", $ref_nasabah, null, ["class" => "form-control select2"])],
            'tgl_kunjungan'       => ['Tgl Kunjungan', Form::text("tgl_kunjungan", $kunjungan->tgl_kunjungan, ["class" => "form-control datepicker", "required" => "required", "id" => "tgl_kunjungan"])],

        ];

        $text = 'membuka form edit ' . $this->title; //.' '.$kunjungan->what;
        $this->log($request, $text, ['kunjungan.id' => $kunjungan->id]);
        return view('Kunjungan::kunjungan_update', array_merge($data, ['title' => $this->title]));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'hasil_kunjungan'     => 'required',
            'id_nasabah'          => 'required',
            'id_status_kunjungan' => 'required',
            'id_user'             => 'required',
            'tgl_kunjungan'       => 'required',

        ]);

        $kunjungan                      = Kunjungan::find($id);
        $kunjungan->hasil_kunjungan     = $request->input("hasil_kunjungan");
        $kunjungan->id_nasabah          = $request->input("id_nasabah");
        $kunjungan->id_status_kunjungan = $request->input("id_status_kunjungan");
        $kunjungan->id_user             = $request->input("id_user");
        $kunjungan->tgl_kunjungan       = $request->input("tgl_kunjungan");

        $kunjungan->updated_by = Auth::id();
        $kunjungan->save();

        $text = 'mengedit ' . $this->title; //.' '.$kunjungan->what;
        $this->log($request, $text, ['kunjungan.id' => $kunjungan->id]);
        return redirect()->route('kunjungan.index')->with('message_success', 'Kunjungan berhasil diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $kunjungan             = Kunjungan::find($id);
        $kunjungan->deleted_by = Auth::id();
        $kunjungan->save();
        $kunjungan->delete();

        $text = 'menghapus ' . $this->title; //.' '.$kunjungan->what;
        $this->log($request, $text, ['kunjungan.id' => $kunjungan->id]);
        return back()->with('message_success', 'Kunjungan berhasil dihapus!');
    }
}
