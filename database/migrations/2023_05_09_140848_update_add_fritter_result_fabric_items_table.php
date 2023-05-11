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
        Schema::table('detail_fabrics', function (Blueprint $table) { 
            $table->decimal('result_qty', 14, 2)->default(0);
            $table->decimal('fritter', 14, 2)->default(0);
        });
        // Schema::table('fabric_items', function (Blueprint $table) { 
        //     $table->decimal('result_qty', 14, 2)->default(0);
        //     $table->decimal('fritter', 14, 2)->default(0);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
