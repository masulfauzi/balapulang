<?php
namespace App\Modules\StatusPinjaman\Controllers;

use App\Helpers\Logger;
use App\Http\Controllers\Controller;
use App\Modules\Log\Models\Log;
use App\Modules\StatusPinjaman\Models\StatusPinjaman;
use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusPinjamanController extends Controller
{
    use Logger;
    protected $log;
    protected $title = "Status Pinjaman";

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function index(Request $request)
    {
        $query = StatusPinjaman::query();
        if ($request->has('search')) {
            $search = $request->get('search');
            // $query->where('name', 'like', "%$search%");
        }
        $data['data'] = $query->paginate(10)->withQueryString();

        $this->log($request, 'melihat halaman manajemen data ' . $this->title);
        return view('StatusPinjaman::statuspinjaman', array_merge($data, ['title' => $this->title]));
    }

    public function create(Request $request)
    {

        $data['forms'] = [
            'status_pinjaman' => ['Status Pinjaman', Form::text("status_pinjaman", old("status_pinjaman"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],
            'status'          => ['Kode Status', Form::text("status", old("status"), ["class" => "form-control", "placeholder" => "", "required" => "required"])],

        ];

        $this->log($request, 'membuka form tambah ' . $this->title);
        return view('StatusPinjaman::statuspinjaman_create', array_merge($data, ['title' => $this->title]));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'status'          => 'required',
            'status_pinjaman' => 'required',

        ]);

        $statuspinjaman                  = new StatusPinjaman();
        $statuspinjaman->status          = $request->input("status");
        $statuspinjaman->status_pinjaman = $request->input("status_pinjaman");

        $statuspinjaman->created_by = Auth::id();
        $statuspinjaman->save();

        $text = 'membuat ' . $this->title; //' baru '.$statuspinjaman->what;
        $this->log($request, $text, ['statuspinjaman.id' => $statuspinjaman->id]);
        return redirect()->route('statuspinjaman.index')->with('message_success', 'Status Pinjaman berhasil ditambahkan!');
    }

    public function show(Request $request, StatusPinjaman $statuspinjaman)
    {
        $data['statuspinjaman'] = $statuspinjaman;

        $text = 'melihat detail ' . $this->title; //.' '.$statuspinjaman->what;
        $this->log($request, $text, ['statuspinjaman.id' => $statuspinjaman->id]);
        return view('StatusPinjaman::statuspinjaman_detail', array_merge($data, ['title' => $this->title]));
    }

    public function edit(Request $request, StatusPinjaman $statuspinjaman)
    {
        $data['statuspinjaman'] = $statuspinjaman;

        $data['forms'] = [
            'status'          => ['Status', Form::text("status", $statuspinjaman->status, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "status"])],
            'status_pinjaman' => ['Status Pinjaman', Form::text("status_pinjaman", $statuspinjaman->status_pinjaman, ["class" => "form-control", "placeholder" => "", "required" => "required", "id" => "status_pinjaman"])],

        ];

        $text = 'membuka form edit ' . $this->title; //.' '.$statuspinjaman->what;
        $this->log($request, $text, ['statuspinjaman.id' => $statuspinjaman->id]);
        return view('StatusPinjaman::statuspinjaman_update', array_merge($data, ['title' => $this->title]));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status'          => 'required',
            'status_pinjaman' => 'required',

        ]);

        $statuspinjaman                  = StatusPinjaman::find($id);
        $statuspinjaman->status          = $request->input("status");
        $statuspinjaman->status_pinjaman = $request->input("status_pinjaman");

        $statuspinjaman->updated_by = Auth::id();
        $statuspinjaman->save();

        $text = 'mengedit ' . $this->title; //.' '.$statuspinjaman->what;
        $this->log($request, $text, ['statuspinjaman.id' => $statuspinjaman->id]);
        return redirect()->route('statuspinjaman.index')->with('message_success', 'Status Pinjaman berhasil diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $statuspinjaman             = StatusPinjaman::find($id);
        $statuspinjaman->deleted_by = Auth::id();
        $statuspinjaman->save();
        $statuspinjaman->delete();

        $text = 'menghapus ' . $this->title; //.' '.$statuspinjaman->what;
        $this->log($request, $text, ['statuspinjaman.id' => $statuspinjaman->id]);
        return back()->with('message_success', 'Status Pinjaman berhasil dihapus!');
    }

}
