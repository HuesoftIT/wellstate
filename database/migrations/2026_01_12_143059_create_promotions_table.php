<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            $table->string('type')->comment('promotion | membership');

            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();

            $table->string('discount_code')->nullable()->unique();

            $table->integer('discount_max_uses')->nullable(); // tổng số lần dùng
            $table->integer('discount_uses_count')->default(0); // đã dùng
            $table->integer('discount_max_uses_per_user')->nullable();

            $table->decimal('discount_min_order_value', 12, 2)->nullable();

            $table->string('discount_type')->comment('percent | fixed');
            $table->decimal('discount_value', 12, 2);
            $table->decimal('discount_max_value', 12, 2)->nullable();

            // Thời gian
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Trạng thái
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
