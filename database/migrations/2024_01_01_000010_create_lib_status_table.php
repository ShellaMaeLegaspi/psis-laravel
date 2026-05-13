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
        Schema::connection('sqlsrv')->create('LIB_Status', function (Blueprint $table) {
            $table->integer('StatusID')->primary();
            $table->string('StatusName', 100);
            $table->string('StatusDescription', 500)->nullable();
            $table->integer('InActive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('LIB_Status');
    }
};
