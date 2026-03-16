<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToImageCategoriesTable extends Migration
{
    public function up(): void
    {
        Schema::table('image_categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('image_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
