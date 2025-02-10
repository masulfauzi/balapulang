<?php

namespace App\Modules\Unit\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Dinas\Models\Dinas;


class Unit extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'unit';
	protected $fillable   = ['*'];	

	public function dinas(){
		return $this->belongsTo(Dinas::class,"id_dinas","id");
	}

}
