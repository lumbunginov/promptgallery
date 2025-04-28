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
        Schema::create('image_styles', function (Blueprint $table) {
            $table->id(); // bigint unsigned, auto-increment
            $table->string('title'); // varchar(255)
            $table->string('code'); // varchar(255)
            $table->foreignId('category_id') // bigint unsigned
                  ->constrained()
                  ->onDelete('cascade');
            $table->text('description'); // text column
            $table->string('image'); // varchar(255) untuk nama file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image__styles');
    }
};
