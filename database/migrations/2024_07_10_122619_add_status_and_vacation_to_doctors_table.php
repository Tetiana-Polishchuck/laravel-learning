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
        Schema::table('doctors', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->boolean('is_on_vacation')->default(false);
            $table->boolean('is_on_sick_leave')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_on_vacation');
            $table->dropColumn('is_on_sick_leave');
        });
    }
};
