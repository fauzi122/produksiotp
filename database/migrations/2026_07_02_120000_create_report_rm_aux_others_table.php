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
        Schema::create('report_rm_aux_others', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('id_master_customers')->nullable();
            $table->unsignedBigInteger('id_sales_orders')->nullable();
            $table->string('shift')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('ketua_regu')->nullable();
            $table->unsignedBigInteger('operator')->nullable();
            $table->unsignedBigInteger('known_by')->nullable();
            $table->unsignedBigInteger('id_cms_users')->nullable();
            $table->string('status')->nullable()->default('Un Posted');
            $table->timestamps();
        });

        Schema::create('report_rm_aux_other_production_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_report_rm_aux_others')->nullable();
            $table->unsignedBigInteger('id_sales_orders')->nullable();
            $table->string('product_info')->nullable();
            $table->datetime('start_time')->nullable();
            $table->datetime('finish_time')->nullable();
            $table->string('lot_number')->nullable();
            $table->string('barcode_end')->nullable();
            $table->string('product')->nullable();
            $table->double('qty_use')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_rm_aux_other_production_results');
        Schema::dropIfExists('report_rm_aux_others');
    }
};
