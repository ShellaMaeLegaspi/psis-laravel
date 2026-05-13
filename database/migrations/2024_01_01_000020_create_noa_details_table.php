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
        Schema::connection('sqlsrv')->create('NOA_Details', function (Blueprint $table) {
            $table->integer('NOADetailsID')->primary()->identity();
            $table->integer('NOAHeaderID');
            $table->integer('ItemID');
            $table->decimal('Quantity', 18, 2)->default(0);
            $table->decimal('UnitCost', 18, 2)->default(0);
            $table->text('Remarks')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('NOAHeaderID');
            $table->index('ItemID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('NOA_Details');
    }
};
