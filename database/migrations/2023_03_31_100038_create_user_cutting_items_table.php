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
        Schema::create('user_cutting_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('qty_fabric', 14, 2)->default(0);
            $table->decimal('qty_sheet', 14, 2)->default(0);
            $table->decimal('qty', 14, 2)->default(0);
            $table->decimal('fritter', 14, 2)->default(0);
            $table->uuid('user_cutting_id');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cutting_items');
    }
};
