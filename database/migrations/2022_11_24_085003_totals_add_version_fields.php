<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TotalsAddVersionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('totals', function (Blueprint $table) {
            $table->integer('legacy')->nullable();
            $table->integer('live')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('totals', function (Blueprint $table) {
            $table->dropColumn('legacy');
            $table->dropColumn('live');
        });
    }
}
