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
        Schema::connection('sqlsrv')->create('SEC_UsersToUsersAccess', function (Blueprint $table) {
            $table->integer('ID')->primary()->identity();
            $table->string('FromEmployeeID', 50);
            $table->string('ToEmployeeID', 50);
            $table->dateTime('DateCreated')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            
            $table->index('FromEmployeeID');
            $table->index('ToEmployeeID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('SEC_UsersToUsersAccess');
    }
};
