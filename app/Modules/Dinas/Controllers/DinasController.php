<?php
namespace App\Modules\Dinas\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Dinas\Models\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DinasController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Dinas";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Dinas::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Dinas::dinas', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'nama_dinas' => ['Nama Dinas', Form::text("nama_dinas", old("nama_dinas"), ["class" => "form-control","placeholder" => "", "required" => "required"]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Dinas::dinas_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'nama_dinas' => 'required',
			
		]);

		$dinas = new Dinas();
		$dinas->nama_dinas = $request->input("nama_dinas");
		
		$dinas->created_by = Auth::id();
		$dinas->save();

		$text = 'membuat '.$this->title; //' baru '.$dinas->what;
		$this->log($request, $text, ['dinas.id' => $dinas->id]);
		return redirect()->route('dinas.index')->with('message_success', 'Dinas berhasil ditambahkan!');
	}

	public function show(Request $request, Dinas $dinas)
	{
		$data['dinas'] = $dinas;

		$text = 'melihat detail '.$this->title;//.' '.$dinas->what;
		$this->log($request, $text, ['dinas.id' => $dinas->id]);
		return view('Dinas::dinas_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Dinas $dinas)
	{
		$data['dinas'] = $dinas;

		
		$data['forms'] = array(
			'nama_dinas' => ['Nama Dinas', Form::text("nama_dinas", $dinas->nama_dinas, ["class" => "form-control","placeholder" => "", "required" => "required", "id" => "nama_dinas"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$dinas->what;
		$this->log($request, $text, ['dinas.id' => $dinas->id]);
		return view('Dinas::dinas_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'nama_dinas' => 'required',
			
		]);
		
		$dinas = Dinas::find($id);
		$dinas->nama_dinas = $request->input("nama_dinas");
		
		$dinas->updated_by = Auth::id();
		$dinas->save();


		$text = 'mengedit '.$this->title;//.' '.$dinas->what;
		$this->log($request, $text, ['dinas.id' => $dinas->id]);
		return redirect()->route('dinas.index')->with('message_success', 'Dinas berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$dinas = Dinas::find($id);
		$dinas->deleted_by = Auth::id();
		$dinas->save();
		$dinas->delete();

		$text = 'menghapus '.$this->title;//.' '.$dinas->what;
		$this->log($request, $text, ['dinas.id' => $dinas->id]);
		return back()->with('message_success', 'Dinas berhasil dihapus!');
	}

}
