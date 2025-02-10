<?php
namespace App\Modules\Unit\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Unit\Models\Unit;
use App\Modules\Dinas\Models\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Unit";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Unit::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Unit::unit', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_dinas = Dinas::all()->pluck('nama_dinas','id');
		
		$data['forms'] = array(
			'id_dinas' => ['Dinas', Form::select("id_dinas", $ref_dinas, null, ["class" => "form-control select2"]) ],
			'nama_unit' => ['Nama Unit', Form::text("nama_unit", old("nama_unit"), ["class" => "form-control","placeholder" => "", "required" => "required"]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Unit::unit_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'id_dinas' => 'required',
			'nama_unit' => 'required',
			
		]);

		$unit = new Unit();
		$unit->id_dinas = $request->input("id_dinas");
		$unit->nama_unit = $request->input("nama_unit");
		
		$unit->created_by = Auth::id();
		$unit->save();

		$text = 'membuat '.$this->title; //' baru '.$unit->what;
		$this->log($request, $text, ['unit.id' => $unit->id]);
		return redirect()->route('unit.index')->with('message_success', 'Unit berhasil ditambahkan!');
	}

	public function show(Request $request, Unit $unit)
	{
		$data['unit'] = $unit;

		$text = 'melihat detail '.$this->title;//.' '.$unit->what;
		$this->log($request, $text, ['unit.id' => $unit->id]);
		return view('Unit::unit_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Unit $unit)
	{
		$data['unit'] = $unit;

		$ref_dinas = Dinas::all()->pluck('nama_dinas','id');
		
		$data['forms'] = array(
			'id_dinas' => ['Dinas', Form::select("id_dinas", $ref_dinas, null, ["class" => "form-control select2"]) ],
			'nama_unit' => ['Nama Unit', Form::text("nama_unit", $unit->nama_unit, ["class" => "form-control","placeholder" => "", "required" => "required", "id" => "nama_unit"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$unit->what;
		$this->log($request, $text, ['unit.id' => $unit->id]);
		return view('Unit::unit_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'id_dinas' => 'required',
			'nama_unit' => 'required',
			
		]);
		
		$unit = Unit::find($id);
		$unit->id_dinas = $request->input("id_dinas");
		$unit->nama_unit = $request->input("nama_unit");
		
		$unit->updated_by = Auth::id();
		$unit->save();


		$text = 'mengedit '.$this->title;//.' '.$unit->what;
		$this->log($request, $text, ['unit.id' => $unit->id]);
		return redirect()->route('unit.index')->with('message_success', 'Unit berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$unit = Unit::find($id);
		$unit->deleted_by = Auth::id();
		$unit->save();
		$unit->delete();

		$text = 'menghapus '.$this->title;//.' '.$unit->what;
		$this->log($request, $text, ['unit.id' => $unit->id]);
		return back()->with('message_success', 'Unit berhasil dihapus!');
	}

}
