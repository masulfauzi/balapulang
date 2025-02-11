<?php
namespace App\Modules\StatusKunjungan\Controllers;

use App\Helpers\Logger;
use App\Http\Controllers\Controller;
use App\Modules\Log\Models\Log;
use App\Modules\StatusKunjungan\Models\StatusKunjungan;
use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusKunjunganController extends Controller
{
    use Logger;
    protected $log;
    protected $title = "Status Kunjungan";

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function index(Request $request)
    {
        $query = StatusKunjungan::query();
        if ($request->has('search')) {
            $search = $request->get('search');
            // $query->where('name', 'like', "%$search%");
        }
        $data['data'] = $query->paginate(10)->withQueryString();

        $this->log($request, 'melihat halaman manajemen data ' . $this->title);
        return view('StatusKunjungan::statuskunjungan', array_merge($data, ['title' => $this->title]));
    }

    public function create(Request $request)
    {

        $data['forms'] = [
            'status_kunjungan' => ['Status Kunjungan', Form::text("status_kunjungan", old("status_kunjungan"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'kode_kunjungan'   => ['Kode Kunjungan', Form::text("kode_kunjungan", old("kode_kunjungan"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],

        ];

        $this->log($request, 'membuka form tambah ' . $this->title);
        return view('StatusKunjungan::statuskunjungan_create', array_merge($data, ['title' => $this->title]));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_kunjungan'   => 'required',
            'status_kunjungan' => 'required',

        ]);

        $statuskunjungan                   = new StatusKunjungan();
        $statuskunjungan->kode_kunjungan   = $request->input("kode_kunjungan");
        $statuskunjungan->status_kunjungan = $request->input("status_kunjungan");

        $statuskunjungan->created_by = Auth::id();
        $statuskunjungan->save();

        $text = 'membuat ' . $this->title; //' baru '.$statuskunjungan->what;
        $this->log($request, $text, ['statuskunjungan.id' => $statuskunjungan->id]);
        return redirect()->route('statuskunjungan.index')->with('message_success', 'Status Kunjungan berhasil ditambahkan!');
    }

    public function show(Request $request, StatusKunjungan $statuskunjungan)
    {
        $data['statuskunjungan'] = $statuskunjungan;

        $text = 'melihat detail ' . $this->title; //.' '.$statuskunjungan->what;
        $this->log($request, $text, ['statuskunjungan.id' => $statuskunjungan->id]);
        return view('StatusKunjungan::statuskunjungan_detail', array_merge($data, ['title' => $this->title]));
    }

    public function edit(Request $request, StatusKunjungan $statuskunjungan)
    {
        $data['statuskunjungan'] = $statuskunjungan;

        $data['forms'] = [
            'kode_kunjungan'   => ['Kode Kunjungan', Form::text("kode_kunjungan", $statuskunjungan->kode_kunjungan, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "kode_kunjungan"])],
            'status_kunjungan' => ['Status Kunjungan', Form::text("status_kunjungan", $statuskunjungan->status_kunjungan, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "status_kunjungan"])],

        ];

        $text = 'membuka form edit ' . $this->title; //.' '.$statuskunjungan->what;
        $this->log($request, $text, ['statuskunjungan.id' => $statuskunjungan->id]);
        return view('StatusKunjungan::statuskunjungan_update', array_merge($data, ['title' => $this->title]));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode_kunjungan'   => 'required',
            'status_kunjungan' => 'required',

        ]);

        $statuskunjungan                   = StatusKunjungan::find($id);
        $statuskunjungan->kode_kunjungan   = $request->input("kode_kunjungan");
        $statuskunjungan->status_kunjungan = $request->input("status_kunjungan");

        $statuskunjungan->updated_by = Auth::id();
        $statuskunjungan->save();

        $text = 'mengedit ' . $this->title; //.' '.$statuskunjungan->what;
        $this->log($request, $text, ['statuskunjungan.id' => $statuskunjungan->id]);
        return redirect()->route('statuskunjungan.index')->with('message_success', 'Status Kunjungan berhasil diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $statuskunjungan             = StatusKunjungan::find($id);
        $statuskunjungan->deleted_by = Auth::id();
        $statuskunjungan->save();
        $statuskunjungan->delete();

        $text = 'menghapus ' . $this->title; //.' '.$statuskunjungan->what;
        $this->log($request, $text, ['statuskunjungan.id' => $statuskunjungan->id]);
        return back()->with('message_success', 'Status Kunjungan berhasil dihapus!');
    }

}
