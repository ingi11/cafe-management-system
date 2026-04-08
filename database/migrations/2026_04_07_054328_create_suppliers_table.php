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
        Schema::create('suppliers', function (Blueprint $col) {
    $col->id();
    $col->string('name');
    $col->string('contact_person')->nullable();
    $col->string('phone')->nullable();
    $col->string('email')->nullable();
    $col->text('address')->nullable();
    $col->timestamps();
});

// Assuming you have an inventory/ingredients table
Schema::table('inventories', function (Blueprint $col) {
    $col->foreignId('supplier_id')->nullable()->constrained();
    $col->decimal('cost_price', 10, 2)->default(0); // What you pay the supplier
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
