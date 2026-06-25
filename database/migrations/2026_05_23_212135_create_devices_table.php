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
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique();
            $table->unsignedBigInteger('parent_id')->nullable()->index('devices_parent_id_foreign');
            $table->unsignedBigInteger('child_id')->nullable()->index('devices_child_id_foreign');
            $table->string('device_name')->nullable();
            $table->string('device_model')->nullable();
            $table->string('os')->nullable();
            $table->string('device_token')->nullable();
            $table->string('fcm_token')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamp('last_active_at')->nullable();
            $table->json('app_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
