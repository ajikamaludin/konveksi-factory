<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('production_id')->nullable();
            $table->uuid('size_id')->nullable();
            $table->uuid('color_id')->nullable();
            $table->decimal('target_quantity', 14, 2)->default(0);
            $table->decimal('finish_quantity', 14, 2)->default(0);
            $table->decimal('reject_quantity', 14, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_items');
    }
};
