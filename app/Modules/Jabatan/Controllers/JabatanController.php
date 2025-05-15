<?php
namespace App\Modules\Jabatan\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Jabatan\Models\Jabatan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Jabatan";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Jabatan::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Jabatan::jabatan', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'jabatan' => ['Jabatan', Form::text("jabatan", old("jabatan"), ["class" => "form-control","placeholder" => "", "required" => "required"]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Jabatan::jabatan_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'jabatan' => 'required',
			
		]);

		$jabatan = new Jabatan();
		$jabatan->jabatan = $request->input("jabatan");
		
		$jabatan->created_by = Auth::id();
		$jabatan->save();

		$text = 'membuat '.$this->title; //' baru '.$jabatan->what;
		$this->log($request, $text, ['jabatan.id' => $jabatan->id]);
		return redirect()->route('jabatan.index')->with('message_success', 'Jabatan berhasil ditambahkan!');
	}

	public function show(Request $request, Jabatan $jabatan)
	{
		$data['jabatan'] = $jabatan;

		$text = 'melihat detail '.$this->title;//.' '.$jabatan->what;
		$this->log($request, $text, ['jabatan.id' => $jabatan->id]);
		return view('Jabatan::jabatan_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Jabatan $jabatan)
	{
		$data['jabatan'] = $jabatan;

		
		$data['forms'] = array(
			'jabatan' => ['Jabatan', Form::text("jabatan", $jabatan->jabatan, ["class" => "form-control","placeholder" => "", "required" => "required", "id" => "jabatan"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$jabatan->what;
		$this->log($request, $text, ['jabatan.id' => $jabatan->id]);
		return view('Jabatan::jabatan_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'jabatan' => 'required',
			
		]);
		
		$jabatan = Jabatan::find($id);
		$jabatan->jabatan = $request->input("jabatan");
		
		$jabatan->updated_by = Auth::id();
		$jabatan->save();


		$text = 'mengedit '.$this->title;//.' '.$jabatan->what;
		$this->log($request, $text, ['jabatan.id' => $jabatan->id]);
		return redirect()->route('jabatan.index')->with('message_success', 'Jabatan berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$jabatan = Jabatan::find($id);
		$jabatan->deleted_by = Auth::id();
		$jabatan->save();
		$jabatan->delete();

		$text = 'menghapus '.$this->title;//.' '.$jabatan->what;
		$this->log($request, $text, ['jabatan.id' => $jabatan->id]);
		return back()->with('message_success', 'Jabatan berhasil dihapus!');
	}

}
