<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     * 【データ長未決定】『日報』テーブル
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('daily_reports');

        Schema::create('daily_reports', function (Blueprint $table) {
            $table->Increments('log_report_id');
            $table->Integer('unique_report_id')->unsigned();
            $table->smallInteger('user_id');
            $table->integer('client_id');
            $table->string('client_dep', 20);
            $table->string('client_name', 20);
            $table->string('title', 60);
            $table->string('visit_contents', 360);
            $table->string('next_step', 360);
            $table->tinyInteger('status');
            $table->datetime('visit_date');
            $table->timestamps();

            $table->foreign('unique_report_id')->references('unique_report_id')->on('reports_list');

            // $table->primary('log_report_id', 'LOG_PRI');
            $table->index('log_report_id', 'LOG_INDEX');
            $table->index('user_id', 'USERID_INDEX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_reports');
    }
}