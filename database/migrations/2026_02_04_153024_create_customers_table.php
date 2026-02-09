<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('membership_id')
                ->nullable()
                ->constrained('memberships')
                ->nullOnDelete();

            $table->string('code')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            $table->string('address')->nullable();

            $table->tinyInteger('gender')->default(0);
            $table->date('birthday')->nullable();

            $table->decimal('total_spent', 15, 2)->default(0);
            $table->integer('point')->default(0);

            $table->boolean('status')->default(true);
            $table->text('note')->nullable();

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
        Schema::dropIfExists('customers');
    }
}
