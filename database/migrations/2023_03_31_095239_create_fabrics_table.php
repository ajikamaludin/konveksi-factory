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
        Schema::create('fabrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->date('order_date')->nullable();
            $table->string('letter_number')->nullable();
            $table->string('composisi')->nullable();
            $table->string('setting_size')->nullable();
            $table->uuid('supplier_id')->nullable();
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
        Schema::dropIfExists('fabrics');
    }
};
