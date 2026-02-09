<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMembershipAndTotalSpentToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Hạng thành viên
            $table->foreignId('membership_id')
                ->nullable()
                ->after('id')
                ->constrained('memberships')
                ->nullOnDelete();

            // Tổng tiền đã chi (cache)
            $table->decimal('total_spent', 15, 2)
                ->default(0)
                ->after('membership_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['membership_id']);
            $table->dropColumn(['membership_id', 'total_spent']);
        });
    }
}
