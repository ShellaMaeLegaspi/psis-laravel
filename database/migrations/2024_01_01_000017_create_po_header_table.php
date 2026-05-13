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
        Schema::connection('sqlsrv')->create('PO_Header', function (Blueprint $table) {
            $table->integer('POHeaderID')->primary()->identity();
            $table->string('POControlNo', 50);
            $table->string('PONo', 50)->nullable();
            $table->string('PreparedBy', 50);
            $table->string('RequisitionedBy', 50)->nullable();
            $table->string('ApprovedBy', 50)->nullable();
            $table->string('EvaluatedBy', 50)->nullable();
            $table->string('FundsAvailableBy', 50)->nullable();
            $table->integer('SupplierID')->nullable();
            $table->string('RespoCenter', 50)->nullable();
            $table->string('DivCode', 50)->nullable();
            $table->integer('Status')->default(0);
            $table->text('Remarks')->nullable();
            $table->date('DateCreated')->nullable();
            $table->date('DateReceived')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('POControlNo');
            $table->index('Status');
            $table->index('SupplierID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('PO_Header');
    }
};
