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
        Schema::table('users', function (Blueprint $table) {
            
            $table->foreignId('group_id')->nullable()->references('id')->on('groups')->onDelete('cascade');
            $table->decimal('balance', 10, 2)->default(25.00);
            $table->string('image_url')->nullable();
            $table->string('github')->nullable();
            $table->string('url')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
