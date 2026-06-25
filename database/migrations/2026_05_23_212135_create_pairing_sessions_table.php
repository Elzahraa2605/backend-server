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
        Schema::create('pairing_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique();
            $table->unsignedBigInteger('parent_id')->index('pairing_sessions_parent_id_foreign');
            $table->string('code')->unique();
            $table->timestamp('expires_at')->useCurrentOnUpdate()->useCurrent();
            $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
            $table->unsignedBigInteger('child_id')->nullable()->index('pairing_sessions_child_id_foreign');
            $table->json('device_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pairing_sessions');
    }
};
