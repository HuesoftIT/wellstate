<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();

            $table->string('code')
                ->comment('Mã chi nhánh, ví dụ: HN01, HCM02');

            $table->string('name')
                ->comment('Tên chi nhánh');

            $table->string('phone')
                ->nullable()
                ->comment('Số điện thoại');


            $table->string('address')
                ->comment('Địa chỉ chi nhánh');

            $table->decimal('latitude', 10, 7)
                ->nullable();

            $table->decimal('longitude', 10, 7)
                ->nullable();

            $table->time('open_time')
                ->nullable()
                ->comment('Giờ mở cửa');

            $table->time('close_time')
                ->nullable()
                ->comment('Giờ đóng cửa');

            $table->boolean('is_active')
                ->default(true)
                ->comment('Chi nhánh đang hoạt động');

            $table->text('note')
                ->nullable();


            $table->timestamps();
            $table->softDeletes();

            $table->unique(['code', 'deleted_at'], 'branches_code_deleted_at_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
}
