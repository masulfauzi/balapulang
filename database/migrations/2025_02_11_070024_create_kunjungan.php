<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // your columns here
            $table->uuid('id_nasabah');
            $table->foreign('id_nasabah')->references('id')->on('nasabah')->onDelete('restrict')->onUpdate('cascade');
            $table->uuid('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->date('tgl_kunjungan');
            $table->uuid('id_status_kunjungan');
            $table->foreign('id_status_kunjungan')->references('id')->on('status_kunjungan')->onDelete('restrict')->onUpdate('cascade');
            $table->text('hasil_kunjungan');

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
        Schema::dropIfExists('kunjungan');
    }
};
