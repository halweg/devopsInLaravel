<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('headline');
            $table->longText('content');
            $table->foreignIdFor(User::class, 'author_id')->constrained('users');
            $table->dateTime('publish_at')->nullable(true);
            $table->boolean('is_published')->default(false);
            $table->string('cover_photo_path')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
