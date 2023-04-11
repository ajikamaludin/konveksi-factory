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
            $table->string('name');
            $table->date('deadline');
            $table->decimal('consumsion', 14, 2)->default(0);
            $table->uuid('material_id');
            $table->uuid('buyer_id');
            $table->uuid('brand_id');
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
