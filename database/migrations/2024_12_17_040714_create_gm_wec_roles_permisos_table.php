<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gm_wec_roles_permisos', function (Blueprint $table) {
            $table->char('ROL_ID', 10);
            $table->char('PERMISO_ID', 10);
            $table->primary(['ROL_ID', 'PERMISO_ID']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_roles_permisos');
    }
};
