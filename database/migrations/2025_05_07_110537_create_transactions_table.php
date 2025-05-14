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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('amusement_id')->nullable()->references('id')->on('amusements')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->references('id')->on('groups')->onDelete('cascade'); // seller
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade'); // buyer

            $table->decimal('stake_amount', 10, 2)->nullable();
            $table->decimal('payout_amount', 10, 2)->nullable();
            $table->foreignId('stamp_id')->nullable()->references('id')->on('stamps')->onDelete('cascade');
            $table->timestamps();

            //$table->foreign('amusement_id')->references('id')->on('amusements')->onDelete('set null');
            //$table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
