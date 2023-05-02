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
        Schema::create('setting_payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('payroll', 14, 2)->default(0);
            $table->decimal('workhours_sunday', 14, 2)->default(0);
            $table->decimal('workhours_monday', 14, 2)->default(0);
            $table->decimal('workhours_tuesday', 14, 2)->default(0);
            $table->decimal('workhours_wednesday', 14, 2)->default(0);
            $table->decimal('workhours_thusday', 14, 2)->default(0);
            $table->decimal('workhours_friday', 14, 2)->default(0);
            $table->decimal('workhours_saturday', 14, 2)->default(0);
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
        Schema::dropIfExists('setting_payrolls');
    }
};
