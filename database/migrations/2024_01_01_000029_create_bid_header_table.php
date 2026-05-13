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
        Schema::connection('sqlsrv')->create('BID_Header', function (Blueprint $table) {
            $table->integer('BIDHeaderID')->primary()->identity();
            $table->string('BIDControlNo', 50);
            $table->string('PreparedBy', 50);
            $table->string('ApprovedBy', 50)->nullable();
            $table->string('RespoCenter', 50)->nullable();
            $table->string('DivCode', 50)->nullable();
            $table->integer('Status')->default(0);
            $table->text('Remarks')->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('BIDControlNo');
            $table->index('Status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('BID_Header');
    }
};
