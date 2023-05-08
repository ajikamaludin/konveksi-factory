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
        Schema::create('cuttings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('style');
            $table->string('name')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->decimal('result_quantity', 14, 2)->default(0);
            $table->decimal('fritter_quantity', 14, 2)->default(0);
            $table->decimal('consumsion', 14, 2)->default(0);
            $table->uuid('material_id')->nullable();
            $table->uuid('buyer_id')->nullable();
            $table->uuid('brand_id')->nullable();
            $table->uuid('production_id')->nullable();
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
        Schema::dropIfExists('cuttings');
    }
};
