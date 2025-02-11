<?php
namespace App\Modules\BankGaji\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\BankGaji\Models\BankGaji;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BankGajiController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Bank Gaji";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = BankGaji::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('BankGaji::bankgaji', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'nama_bank' => ['Nama Bank', Form::text("nama_bank", old("nama_bank"), ["class" => "form-control","placeholder" => "", "required" => "required"]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('BankGaji::bankgaji_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'nama_bank' => 'required',
			
		]);

		$bankgaji = new BankGaji();
		$bankgaji->nama_bank = $request->input("nama_bank");
		
		$bankgaji->created_by = Auth::id();
		$bankgaji->save();

		$text = 'membuat '.$this->title; //' baru '.$bankgaji->what;
		$this->log($request, $text, ['bankgaji.id' => $bankgaji->id]);
		return redirect()->route('bankgaji.index')->with('message_success', 'Bank Gaji berhasil ditambahkan!');
	}

	public function show(Request $request, BankGaji $bankgaji)
	{
		$data['bankgaji'] = $bankgaji;

		$text = 'melihat detail '.$this->title;//.' '.$bankgaji->what;
		$this->log($request, $text, ['bankgaji.id' => $bankgaji->id]);
		return view('BankGaji::bankgaji_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, BankGaji $bankgaji)
	{
		$data['bankgaji'] = $bankgaji;

		
		$data['forms'] = array(
			'nama_bank' => ['Nama Bank', Form::text("nama_bank", $bankgaji->nama_bank, ["class" => "form-control","placeholder" => "", "required" => "required", "id" => "nama_bank"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$bankgaji->what;
		$this->log($request, $text, ['bankgaji.id' => $bankgaji->id]);
		return view('BankGaji::bankgaji_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'nama_bank' => 'required',
			
		]);
		
		$bankgaji = BankGaji::find($id);
		$bankgaji->nama_bank = $request->input("nama_bank");
		
		$bankgaji->updated_by = Auth::id();
		$bankgaji->save();


		$text = 'mengedit '.$this->title;//.' '.$bankgaji->what;
		$this->log($request, $text, ['bankgaji.id' => $bankgaji->id]);
		return redirect()->route('bankgaji.index')->with('message_success', 'Bank Gaji berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$bankgaji = BankGaji::find($id);
		$bankgaji->deleted_by = Auth::id();
		$bankgaji->save();
		$bankgaji->delete();

		$text = 'menghapus '.$this->title;//.' '.$bankgaji->what;
		$this->log($request, $text, ['bankgaji.id' => $bankgaji->id]);
		return back()->with('message_success', 'Bank Gaji berhasil dihapus!');
	}

}
