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
            $table->string('picture')->nullable();
            $table->string('title');
            $table->string('author');
            $table->year('published_year')->nullable();
            $table->string('isbn')->nullable()->unique();
            $table->text('description')->nullable();
            $table->decimal('price');
            $table->timestamps();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
