<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();

            // ===== RELATION =====
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->nullOnDelete();

            // ===== COMMENT TREE =====
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('post_comments')
                ->cascadeOnDelete();

            // ===== CONTENT =====
            $table->text('content');

            // ===== STATUS =====
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_spam')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_comments');
    }
}
