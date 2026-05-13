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
        Schema::connection('sqlsrv')->create('LIB_Parameters', function (Blueprint $table) {
            $table->integer('ID')->primary()->identity();
            $table->string('ParameterName', 100);
            $table->string('ParameterCode', 100);
            $table->string('ParameterValue', 500)->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('ParameterName');
            $table->index('ParameterCode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('LIB_Parameters');
    }
};
