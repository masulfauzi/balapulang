<?php
namespace App\Modules\Nasabah\Models;

use App\Helpers\UsesUuid;
use App\Modules\BankGaji\Models\BankGaji;
use App\Modules\Unit\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Nasabah extends Model
{
    use SoftDeletes;
    use UsesUuid;

    protected $casts    = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    protected $table    = 'nasabah';
    protected $fillable = ['*'];

    public function bankGaji()
    {
        return $this->belongsTo(BankGaji::class, "id_bank_gaji", "id");
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, "id_unit", "id");
    }

    public static function get_nasabah_blm_kunjungan()
    {
        return DB::select('SELECT * FROM nasabah AS n WHERE n.id NOT IN (SELECT k.id_nasabah FROM kunjungan AS k GROUP BY id_nasabah)')->paginate(10);
    }

}
