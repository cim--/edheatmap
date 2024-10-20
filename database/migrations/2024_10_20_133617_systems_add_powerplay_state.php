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
            $table->string('power')->index()->nullable();
            $table->enum('powerstate', ['None', 'Exploited', 'Fortified', 'Stronghold'])->default('None');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropIndex(['power']);
            $table->dropColumn('power');
            $table->dropColumn('powerstate');
        });
    }
};
