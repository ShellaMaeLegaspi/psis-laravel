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
        Schema::connection('sqlsrv')->create('IAR_Details', function (Blueprint $table) {
            $table->integer('IARDetailsID')->primary()->identity();
            $table->integer('IARHeaderID');
            $table->integer('ItemID');
            $table->string('PRNo', 50)->nullable();
            $table->integer('PODetailsID')->nullable();
            $table->string('ProjectCode', 50)->nullable();
            $table->decimal('Quantity', 18, 2)->default(0);
            $table->decimal('UnitCost', 18, 2)->default(0);
            $table->text('Remarks')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('IARHeaderID');
            $table->index('ItemID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('IAR_Details');
    }
};
