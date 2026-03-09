<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::create('promotion_usages', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('promotion_id');

            $table->unsignedBigInteger('booking_id')->nullable();

            $table->string('phone_number', 20);

            $table->decimal('discount_amount', 12, 2)->default(0);

            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            // Index để query nhanh
            $table->index('promotion_id');
            $table->index('phone_number');

            // Query thường gặp
            $table->index(['promotion_id', 'phone_number']);

            // Foreign key
            $table->foreign('promotion_id')
                ->references('id')
                ->on('promotions')
                ->cascadeOnDelete();

            // tránh 1 booking dùng promo nhiều lần
            $table->unique(['promotion_id', 'booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_usages');
    }
}
