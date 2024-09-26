<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCoinsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users_coins_history', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('entity_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('type', 255)->comment('Initial | Bet | Event | Fix | Old Bot | Gift | Daily | Transfer | Troll');
            $table->text('description')->nullable();
            $table->timestamps();

            // Keys and Indexes
            $table->index('user_id');
            $table->index('type');

            // Foreign Key Constraints
            $table->foreignId('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users_coins_history');
    }
}