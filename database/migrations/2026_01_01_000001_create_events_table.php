<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('registration_deadline')->nullable();
            $table->integer('capacity')->default(0);
            $table->string('status')->default('draft'); // draft, published, completed, cancelled
            $table->string('banner_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
