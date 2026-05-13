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
        Schema::connection('sqlsrv')->create('LIB_Items', function (Blueprint $table) {
            $table->integer('ItemID')->primary();
            $table->string('ItemCode', 50);
            $table->string('SpecDetails', 500)->nullable();
            $table->string('Unit', 50)->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('ItemCode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('LIB_Items');
    }
};
