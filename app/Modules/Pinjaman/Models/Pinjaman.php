<?php

namespace App\Modules\Pinjaman\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Nasabah\Models\Nasabah;
use App\Modules\StatusPinjaman\Models\StatusPinjaman;


class Pinjaman extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'pinjaman';
	protected $fillable   = ['*'];	

	public function nasabah(){
		return $this->belongsTo(Nasabah::class,"id_nasabah","id");
	}
public function statusPinjaman(){
		return $this->belongsTo(StatusPinjaman::class,"id_status_pinjaman","id");
	}

}
