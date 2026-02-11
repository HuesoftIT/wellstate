<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRoomTypeIdFromBookingsTable extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'room_type_id')) {
                $table->dropColumn('room_type_id');
            }
        });
    }


    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('room_type_id')
                ->after('branch_id');

            // Nếu muốn add lại foreign key
            // $table->foreign('room_type_id')->references('id')->on('room_types');
        });
    }
}
