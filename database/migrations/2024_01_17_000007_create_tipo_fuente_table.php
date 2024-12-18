<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_tipo_fuente', function (Blueprint $table) {
            $table->string('tipofue_id', 256)->primary();
            $table->string('tipfue_nombrefuente', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_tipo_fuente');
    }
};
