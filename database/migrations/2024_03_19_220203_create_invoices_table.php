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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("invoice_number",50);
            $table->date("inovices_date");
            $table->date("due_date");
            $table->string("product");
            $table->bigInteger("section_ID")->unsigned();
            $table->foreign("section_ID")->references('id')->on('sections')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('Amount_collection',8,2)->nullable();
            $table->decimal('Amount_Commission',8,2);
            $table->decimal("discount",8,2);
            $table->decimal("value_vat",8,2);
            $table->decimal("total",8,2);
            $table->string("rate_vat",999);
            $table->string("status",50);
            $table->integer("value_status");
            $table->text("note")->nullable();
            $table->date('Payment_Date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
