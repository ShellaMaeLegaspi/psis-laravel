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
        Schema::connection('sqlsrv')->create('RIS_Details', function (Blueprint $table) {
            $table->integer('RISDetailsID')->primary()->identity();
            $table->integer('RISHeaderID');
            $table->integer('ItemID');
            $table->integer('IARDetailsID')->nullable();
            $table->integer('PARDetailsID')->nullable();
            $table->decimal('Quantity', 18, 2)->default(0);
            $table->text('Remarks')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('RISHeaderID');
            $table->index('ItemID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('RIS_Details');
    }
};
