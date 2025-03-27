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
        Schema::create('lockers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('modbus_address');
            $table->string('coil_register');
            $table->string('status_register')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('locker_id')->nullable()->constrained('lockers');
        });
    }
};
