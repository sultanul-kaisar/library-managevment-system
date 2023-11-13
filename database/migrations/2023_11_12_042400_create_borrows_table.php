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
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('book_id');
            $table->string('borrow_code')->nullable();
            $table->integer('qty')->nullable();
            $table->text('message')->nullable();
            $table->date('borrow_date')->nullable();
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'returned'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};
