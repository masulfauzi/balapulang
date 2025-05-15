<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nasabah', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // your columns here
            $table->string('nama_nasabah');
            $table->uuid('id_unit');
            $table->foreign('id_unit')->references('id')->on('unit')->onDelete('restrict')->onUpdate('cascade');
            $table->uuid('id_jabatan');
            $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('restrict')->onUpdate('cascade');
            $table->string('nip');
            $table->string('cif');
            $table->uuid('id_bank_gaji');
            $table->foreign('id_bank_gaji')->references('id')->on('bank_gaji')->onDelete('restrict')->onUpdate('cascade');
            $table->text('keterangan');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->text('alamat');


            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nasabah');
    }
};
