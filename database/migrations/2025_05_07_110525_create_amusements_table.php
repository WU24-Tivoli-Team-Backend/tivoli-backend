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
        Schema::create('amusements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->string('name', 255);
            $table->enum('type', ['attraction', 'game']);
            $table->string('description');
            $table->string('image_url', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->timestamps();
            //$table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amusements');
    }
};
