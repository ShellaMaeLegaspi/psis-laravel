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
        Schema::connection('sqlsrv')->create('SEC_GroupsAccess', function (Blueprint $table) {
            $table->integer('GroupID');
            $table->integer('AccessID');
            $table->string('CreatedBy', 50)->nullable();
            $table->date('DateCreated')->nullable();
            $table->integer('InActive')->default(0);
            
            $table->primary(['GroupID', 'AccessID']);
            $table->index('GroupID');
            $table->index('AccessID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('SEC_GroupsAccess');
    }
};
