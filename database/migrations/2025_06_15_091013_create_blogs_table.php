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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->enum('department', ['Computer Technology',
             'Civil Technology', 'Electrical Technology', 'Mechanical Technology', 
             'Electronics Technology', 'Power Technology', 'Chemical Technology'])->nullable();
            $table->string('featured_image_url')->nullable();
            $table->mediumText('content');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
