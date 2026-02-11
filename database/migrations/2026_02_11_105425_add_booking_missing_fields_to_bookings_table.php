<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingMissingFieldsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->unsignedBigInteger('branch_room_type_id')
                ->after('branch_id');

            $table->string('promotion_code', 50)
                ->nullable()
                ->after('promotion_id');

            $table->json('promotion_snapshot')
                ->nullable()
                ->after('promotion_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['branch_room_type_id']);
            $table->dropIndex(['promotion_code']);

            $table->dropColumn([
                'branch_room_type_id',
                'promotion_code',
                'promotion_snapshot',
            ]);
        });
    }
}
