<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddApplyScopeToPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->enum('apply_scope', [
                'booking',
                'service',
                'room'
            ])
                ->default('booking')
                ->after('type');
        });

        /**
         * Backfill data cũ
         * Nếu trước đây promotion có rules -> mặc định là service
         */
        DB::table('promotions')
            ->update([
                'apply_scope' => 'service'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('apply_scope');
        });
    }
}
