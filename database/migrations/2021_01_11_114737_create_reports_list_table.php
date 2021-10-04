<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports_list', function (Blueprint $table) {
            $table->increments('unique_report_id');
            $table->Integer('log_report_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            // $table->primary('unique_report_id', 'UNIQUE_PRI');
            $table->index('unique_report_id', 'UNIQUE_INDEX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports_list');
    }
}
