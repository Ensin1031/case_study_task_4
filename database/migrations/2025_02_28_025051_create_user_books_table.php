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
        Schema::create('user_books', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->comment('Пользователь');
            $table->unsignedBigInteger('book_id')->comment('Книга');

            $table->index('user_id', 'user_book_user_idx');
            $table->index('book_id', 'user_book_book_idx');

            $table->foreign('user_id', 'user_book_user_fk')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id', 'user_book_book_fk')->references('id')->on('books')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('user_books');
        }
    }
};
