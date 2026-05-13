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
        Schema::connection('sqlsrv')->create('SEC_Groups', function (Blueprint $table) {
            $table->integer('GroupID')->primary();
            $table->string('GroupName', 100);
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateCreated')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->date('DateUpdated')->nullable();
            $table->integer('InActive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('SEC_Groups');
    }
};
