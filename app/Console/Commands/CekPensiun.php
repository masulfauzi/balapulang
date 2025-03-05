<?php

namespace App\Console\Commands;

use App\Modules\Nasabah\Models\Nasabah;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CekPensiun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cek-pensiun';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = Nasabah::whereIsPensiun('0')->get();

        foreach ($data as $data_nasabah) {
            $tgl_lahir = Carbon::parse($data_nasabah->tgl_lahir);
            $selisih = Carbon::now()->diffInYears($tgl_lahir);

            if ($selisih >= 60) {
                $nasabah = Nasabah::find($data_nasabah->id);
                $nasabah->is_pensiun = '1';
                $nasabah->save();

                $this->info("Update data pensiun untuk: " . $data_nasabah->nama_nasabah);
            }


        }
    }
}
