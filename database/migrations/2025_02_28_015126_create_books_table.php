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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Наименование');
            $table->text('description')->comment('Краткое описание');
            $table->enum('status', [1, 2, 3])->default(1)->comment('Статус');  // 1 - Черновик, 2 - Опубликовано, 3 - Снято с публикации
            $table->float('price')->default(0.00)->comment('Цена');
            $table->integer('published_year')->default(0)->comment('Год публикации');
            $table->string('image')->comment('Изображение')->nullable();

            $table->unsignedBigInteger('category_id')->comment('Категория');
            $table->unsignedBigInteger('author_id')->comment('Автор');

            $table->index('category_id', 'book_category_idx');
            $table->index('author_id', 'book_author_idx');

            $table->foreign('category_id', 'book_category_fk')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('author_id', 'book_author_fk')->references('id')->on('authors')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('books');
        }
    }
};
