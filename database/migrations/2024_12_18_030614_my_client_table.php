<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MyClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->string('slug', 100)->unique();
            $table->enum('is_project', ['0', '1'])->default('0');
            $table->char('self_capture', 1)->default('1');
            $table->char('client_prefix', 4);
            $table->string('client_logo', 255)->default('no-image.jpg');
            $table->text('address')->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->timestamps(0);
            $table->softDeletes(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}