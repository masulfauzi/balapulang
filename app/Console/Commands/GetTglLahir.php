<?php

namespace App\Console\Commands;

use App\Modules\Nasabah\Models\Nasabah;
use Illuminate\Console\Command;

class GetTglLahir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-tgl-lahir';

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
        $nasabah = Nasabah::whereNotNull('nip')->get();

        foreach ($nasabah as $data_nasabah) {
            $tgl = substr($data_nasabah->nip, 6, 2);
            $bln = substr($data_nasabah->nip, 4, 2);
            $thn = substr($data_nasabah->nip, 0, 4);

            $data = Nasabah::find($data_nasabah->id);
            $data->tgl_lahir = $thn . "-" . $bln . "-" . $tgl;
            $data->save();

            $this->info("Mendapatkan data tgl lahir untuk :" . $data_nasabah->nama_nasabah);
        }


        $this->info("Selesai mendapatkan data tgl lahir");


    }
}
