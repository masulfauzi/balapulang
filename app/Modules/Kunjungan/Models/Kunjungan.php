<?php

namespace App\Modules\Kunjungan\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Nasabah\Models\Nasabah;
use App\Modules\StatusKunjungan\Models\StatusKunjungan;
use App\Modules\Users\Models\Users;


class Kunjungan extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'kunjungan';
	protected $fillable   = ['*'];	

	public function nasabah(){
		return $this->belongsTo(Nasabah::class,"id_nasabah","id");
	}
public function statusKunjungan(){
		return $this->belongsTo(StatusKunjungan::class,"id_status_kunjungan","id");
	}
public function user(){
		return $this->belongsTo(Users::class,"id_user","id");
	}

}
