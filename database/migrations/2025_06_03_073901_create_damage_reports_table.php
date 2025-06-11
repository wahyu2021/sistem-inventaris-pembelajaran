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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')
                    ->constrained('locations')
                    ->onDelete('cascade');
            $table->foreignId('reported_by_id_user')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('cascade');
            $table->string('reported_by');
            $table->text('description');
            $table->string('severity')->default('ringan');
            $table->string('status')->default('dilaporkan');
            $table->string('image_damage')->nullable();
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
