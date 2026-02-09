<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingGuestServiceRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_guest_service_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_guest_service_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_room_type_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('room_type_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_guest_service_rooms');
    }
}
