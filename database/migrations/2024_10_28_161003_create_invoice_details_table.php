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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();

             // Foreign key for invoices
             $table->unsignedBigInteger('invoice_id');
             $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

             // Foreign key for sections
             $table->unsignedBigInteger('section_id');
             $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');


             // Invoice details
             $table->string('status', 50);
             $table->integer('value_status');
             $table->date('payment_date')->nullable();
             $table->text('note')->nullable();

             // Reference to the user responsible
             $table->unsignedBigInteger('user_id');
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
