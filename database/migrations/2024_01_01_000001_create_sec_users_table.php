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
        Schema::connection('sqlsrv')->create('SEC_Users', function (Blueprint $table) {
            $table->integer('UserID')->primary();
            $table->string('EmployeeID', 50);
            $table->integer('GroupID')->default(2);
            $table->integer('GroupID_RCEF')->default(2);
            $table->integer('CanViewAll')->default(0);
            $table->integer('Locked')->default(0);
            $table->integer('UserLevel')->default(1);
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->dateTime('DateTimeUpdated')->nullable();
            $table->date('DateUpdated')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('EmployeeID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('SEC_Users');
    }
};
