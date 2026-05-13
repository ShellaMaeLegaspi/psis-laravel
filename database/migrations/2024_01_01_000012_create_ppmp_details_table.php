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
        Schema::connection('sqlsrv')->create('PPMP_Details', function (Blueprint $table) {
            $table->integer('PPMPDetailsID')->primary()->identity();
            $table->integer('PPMPHeaderID');
            $table->integer('ItemID');
            $table->string('ProjectCode', 50)->nullable();
            $table->decimal('Quantity', 18, 2)->default(0);
            $table->decimal('UnitCost', 18, 2)->default(0);
            $table->decimal('TotalCost', 18, 2)->default(0);
            $table->text('Remarks')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('PPMPHeaderID');
            $table->index('ItemID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('PPMP_Details');
    }
};
