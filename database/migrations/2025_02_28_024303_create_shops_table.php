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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [1, 2, 3])->default(1)->comment('Статус');  // 1 - В корзине, 2 - Оплачено, 3 - Продано
            $table->date('paid_at')->nullable()->comment('Дата оплаты');
            $table->date('buy_at')->nullable()->comment('Дата продажи');

            $table->unsignedBigInteger('user_id')->comment('Пользователь');
            $table->unsignedBigInteger('book_id')->comment('Книга');

            $table->index('user_id', 'shop_user_idx');
            $table->index('book_id', 'shop_book_idx');

            $table->foreign('user_id', 'shop_user_fk')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id', 'shop_book_fk')->references('id')->on('books')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('shops');
        }
    }
};
