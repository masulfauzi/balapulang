<?php
namespace App\Modules\Pinjaman\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Pinjaman\Models\Pinjaman;
use App\Modules\Nasabah\Models\Nasabah;
use App\Modules\StatusPinjaman\Models\StatusPinjaman;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PinjamanController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Pinjaman";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Pinjaman::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Pinjaman::pinjaman', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_nasabah = Nasabah::all()->pluck('cif','id');
		$ref_status_pinjaman = StatusPinjaman::all()->pluck('created_by','id');
		
		$data['forms'] = array(
			'id_nasabah' => ['Nasabah', Form::select("id_nasabah", $ref_nasabah, null, ["class" => "form-control select2"]) ],
			'id_status_pinjaman' => ['Status Pinjaman', Form::select("id_status_pinjaman", $ref_status_pinjaman, null, ["class" => "form-control select2"]) ],
			'no_pinjaman' => ['No Pinjaman', Form::text("no_pinjaman", old("no_pinjaman"), ["class" => "form-control","placeholder" => "", "required" => "required"]) ],
			'plafon' => ['Plafon', Form::text("plafon", old("plafon"), ["class" => "form-control","placeholder" => "", "required" => "required"]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Pinjaman::pinjaman_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'id_nasabah' => 'required',
			'id_status_pinjaman' => 'required',
			'no_pinjaman' => 'required',
			'plafon' => 'required',
			
		]);

		$pinjaman = new Pinjaman();
		$pinjaman->id_nasabah = $request->input("id_nasabah");
		$pinjaman->id_status_pinjaman = $request->input("id_status_pinjaman");
		$pinjaman->no_pinjaman = $request->input("no_pinjaman");
		$pinjaman->plafon = $request->input("plafon");
		
		$pinjaman->created_by = Auth::id();
		$pinjaman->save();

		$text = 'membuat '.$this->title; //' baru '.$pinjaman->what;
		$this->log($request, $text, ['pinjaman.id' => $pinjaman->id]);
		return redirect()->route('pinjaman.index')->with('message_success', 'Pinjaman berhasil ditambahkan!');
	}

	public function show(Request $request, Pinjaman $pinjaman)
	{
		$data['pinjaman'] = $pinjaman;

		$text = 'melihat detail '.$this->title;//.' '.$pinjaman->what;
		$this->log($request, $text, ['pinjaman.id' => $pinjaman->id]);
		return view('Pinjaman::pinjaman_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Pinjaman $pinjaman)
	{
		$data['pinjaman'] = $pinjaman;

		$ref_nasabah = Nasabah::all()->pluck('cif','id');
		$ref_status_pinjaman = StatusPinjaman::all()->pluck('created_by','id');
		
		$data['forms'] = array(
			'id_nasabah' => ['Nasabah', Form::select("id_nasabah", $ref_nasabah, null, ["class" => "form-control select2"]) ],
			'id_status_pinjaman' => ['Status Pinjaman', Form::select("id_status_pinjaman", $ref_status_pinjaman, null, ["class" => "form-control select2"]) ],
			'no_pinjaman' => ['No Pinjaman', Form::text("no_pinjaman", $pinjaman->no_pinjaman, ["class" => "form-control","placeholder" => "", "required" => "required", "id" => "no_pinjaman"]) ],
			'plafon' => ['Plafon', Form::text("plafon", $pinjaman->plafon, ["class" => "form-control","placeholder" => "", "required" => "required", "id" => "plafon"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$pinjaman->what;
		$this->log($request, $text, ['pinjaman.id' => $pinjaman->id]);
		return view('Pinjaman::pinjaman_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'id_nasabah' => 'required',
			'id_status_pinjaman' => 'required',
			'no_pinjaman' => 'required',
			'plafon' => 'required',
			
		]);
		
		$pinjaman = Pinjaman::find($id);
		$pinjaman->id_nasabah = $request->input("id_nasabah");
		$pinjaman->id_status_pinjaman = $request->input("id_status_pinjaman");
		$pinjaman->no_pinjaman = $request->input("no_pinjaman");
		$pinjaman->plafon = $request->input("plafon");
		
		$pinjaman->updated_by = Auth::id();
		$pinjaman->save();


		$text = 'mengedit '.$this->title;//.' '.$pinjaman->what;
		$this->log($request, $text, ['pinjaman.id' => $pinjaman->id]);
		return redirect()->route('pinjaman.index')->with('message_success', 'Pinjaman berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$pinjaman = Pinjaman::find($id);
		$pinjaman->deleted_by = Auth::id();
		$pinjaman->save();
		$pinjaman->delete();

		$text = 'menghapus '.$this->title;//.' '.$pinjaman->what;
		$this->log($request, $text, ['pinjaman.id' => $pinjaman->id]);
		return back()->with('message_success', 'Pinjaman berhasil dihapus!');
	}

}
