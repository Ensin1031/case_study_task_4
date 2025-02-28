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
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->integer('rent_period_days')->default(0)->comment('Дней аренды');
            $table->enum('status', [1, 2, 3, 4])->default(1)->comment('Статус');  // 1 - Черновик, 2 - Арендовано, 3 - Просрочено, 4 - Возвращено

            $table->unsignedBigInteger('user_id')->comment('Пользователь');
            $table->unsignedBigInteger('book_id')->comment('Книга');

            $table->index('user_id', 'rent_user_idx');
            $table->index('book_id', 'rent_book_idx');

            $table->foreign('user_id', 'rent_user_fk')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id', 'rent_book_fk')->references('id')->on('books')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('rents');
        }
    }
};
