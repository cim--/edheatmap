<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->integer('powercps')->unsigned()->nullable();
            $table->integer('acquisition')->unsigned()->nullable();
            $table->integer('reinforcement')->unsigned()->nullable();
            $table->integer('undermining')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropColumn('undermining');
            $table->dropColumn('reinforcement');
            $table->dropColumn('acquisition');
            $table->dropColumn('powercps');
        });
    }
};
