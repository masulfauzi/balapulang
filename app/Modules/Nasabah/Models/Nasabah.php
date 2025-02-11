<?php

namespace App\Modules\Nasabah\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\BankGaji\Models\BankGaji;
use App\Modules\Unit\Models\Unit;


class Nasabah extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'nasabah';
	protected $fillable   = ['*'];	

	public function bankGaji(){
		return $this->belongsTo(BankGaji::class,"id_bank_gaji","id");
	}
public function unit(){
		return $this->belongsTo(Unit::class,"id_unit","id");
	}

}
