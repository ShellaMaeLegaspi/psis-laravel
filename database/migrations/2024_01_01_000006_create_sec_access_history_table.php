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
        Schema::connection('sqlsrv')->create('SEC_Access_History', function (Blueprint $table) {
            $table->integer('ID')->primary()->identity();
            $table->integer('AccessID');
            $table->integer('AccessID_RCEF');
            $table->integer('UserID');
            $table->string('EmployeeID', 50);
            $table->string('UpdatedBy', 50)->nullable();
            $table->dateTime('DateUpdated')->nullable();
            
            $table->index('UserID');
            $table->index('EmployeeID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('SEC_Access_History');
    }
};
