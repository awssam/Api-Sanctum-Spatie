<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('signature');
            $table->string('options')->nullable();
            $table->integer('offset')->nullable();
            $table->integer('limit')->nullable();
            $table->integer('previous_count')->nullable();
            $table->integer('next_count')->nullable();
            $table->boolean('is_runing')->nullable();
            $table->timestamp('last_executed_at')->nullable();
            $table->boolean('last_executed_failed')->nullable();
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
        Schema::dropIfExists('cron_jobs');
    }
}
