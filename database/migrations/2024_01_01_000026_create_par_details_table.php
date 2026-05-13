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
        Schema::connection('sqlsrv')->create('PAR_Details', function (Blueprint $table) {
            $table->integer('PARDetailsID')->primary()->identity();
            $table->integer('PARHeaderID');
            $table->integer('ItemID');
            $table->integer('IARDetailsID')->nullable();
            $table->integer('PropertyID')->nullable();
            $table->string('PropertyNo', 50)->nullable();
            $table->text('Remarks')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->index('PARHeaderID');
            $table->index('ItemID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('PAR_Details');
    }
};
