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
        Schema::connection('sqlsrv')->create('PPMP_Header', function (Blueprint $table) {
            $table->integer('PPMPHeaderID')->primary()->identity();
            $table->string('PPMPControlNo', 50);
            $table->string('PPMPNo', 50)->nullable();
            $table->string('PreparedBy', 50);
            $table->string('ApprovedBy', 50)->nullable();
            $table->string('CertifiedBy', 50)->nullable();
            $table->string('ReceivedBy', 50)->nullable();
            $table->string('RespoCenter', 50)->nullable();
            $table->string('DivCode', 50)->nullable();
            $table->integer('Status')->default(0);
            $table->text('Remarks')->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('PPMPControlNo');
            $table->index('Status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('PPMP_Header');
    }
};
