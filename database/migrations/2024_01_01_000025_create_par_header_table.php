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
        Schema::connection('sqlsrv')->create('PAR_Header', function (Blueprint $table) {
            $table->integer('PARHeaderID')->primary()->identity();
            $table->string('PARControlNo', 50);
            $table->string('PARNo', 50)->nullable();
            $table->string('PreparedBy', 50);
            $table->string('AccountableOfficer', 50)->nullable();
            $table->string('CoAccountableOfficer', 50)->nullable();
            $table->string('RespoCenter', 50)->nullable();
            $table->string('DivCode', 50)->nullable();
            $table->string('Particulars', 500)->nullable();
            $table->string('TrackingNo', 50)->nullable();
            $table->integer('Status')->default(0);
            $table->text('Remarks')->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('PARControlNo');
            $table->index('Status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('PAR_Header');
    }
};
