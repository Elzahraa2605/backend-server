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
        Schema::create('web_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->char('uuid', 36);
            $table->unsignedBigInteger('child_id');
            $table->string('url');
            $table->string('domain');
            $table->string('title')->nullable();
            $table->enum('category', ['social', 'educational', 'entertainment', 'shopping', 'unknown'])->default('unknown');
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('visited_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_histories');
    }
};
