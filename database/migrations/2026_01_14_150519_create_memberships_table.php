<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();

            $table->string('code')
                ->comment('bronze, silver, gold');

            $table->string('name')
                ->comment('Tên hiển thị: Đồng, Bạc, Vàng');

            $table->decimal('min_total_spent', 15, 2)
                ->default(0)
                ->comment('Tổng chi tiêu tối thiểu để đạt hạng');

            $table->unsignedInteger('priority')
                ->comment('Độ ưu tiên, càng cao càng VIP');

            $table->json('benefits')
                ->nullable()
                ->comment('Quyền lợi mở rộng (JSON)');

            $table->longText('description')->nullable();
            $table->boolean('is_active')
                ->default(true);

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['code', 'deleted_at'], 'memberships_code_deleted_at_unique');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
}
