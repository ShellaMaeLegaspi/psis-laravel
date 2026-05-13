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
        Schema::connection('sqlsrv')->create('SEC_Access', function (Blueprint $table) {
            $table->integer('AccessID')->primary();
            $table->string('AccessDescription', 200);
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->integer('InActive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('SEC_Access');
    }
};
