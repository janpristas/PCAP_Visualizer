<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('src_ip')->nullable();
            $table->string('dst_ip')->nullable();
            $table->string('src_mac')->nullable();
            $table->string('dst_mac')->nullable();
            $table->string('src_port')->nullable();
            $table->string('dst_port')->nullable();
            $table->double('time_relative', 20, 10)->nullable();
            $table->string('trans_id')->nullable();
            $table->string('unit_id')->nullable();
            $table->string('func_code')->nullable();
            $table->string('bit_cnt')->nullable();
            $table->string('byte_cnt')->nullable();
            $table->integer('ip_len')->nullable();
            $table->string('time')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('stations');
    }
}
