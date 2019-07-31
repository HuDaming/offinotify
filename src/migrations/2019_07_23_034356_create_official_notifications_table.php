<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficialNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->comment('通知类型');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('trigger_id')->nullable();
            $table->string('trigger_type')->nullable();
            $table->text('data');
            $table->dateTime('read_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('official_notifications');
    }
}
