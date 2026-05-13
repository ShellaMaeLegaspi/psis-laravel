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
        Schema::connection('sqlsrv')->create('PO_Details', function (Blueprint $table) {
            $table->integer('PODetailsID')->primary()->identity();
            $table->integer('POHeaderID');
            $table->integer('ItemID');
            $table->string('PRNo', 50)->nullable();
            $table->integer('PRDetailsID')->nullable();
            $table->integer('NOADetailsID')->nullable();
            $table->decimal('Quantity', 18, 2)->default(0);
            $table->decimal('UnitCost', 18, 2)->default(0);
            $table->decimal('TotalCost', 18, 2)->default(0);
            $table->text('Remarks')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('POHeaderID');
            $table->index('ItemID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('PO_Details');
    }
};
