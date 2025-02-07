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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // your columns here
            $table->uuid('id_nasabah');
            $table->foreign('id_nasabah')->references('id')->on('nasabah')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('plafon');
            $table->string('no_pinjaman');
            $table->uuid('id_status_pinjaman');
            $table->foreign('id_status_pinjaman')->references('id')->on('status_pinjaman')->onDelete('restrict')->onUpdate('cascade');

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
        Schema::dropIfExists('pinjaman');
    }
};
