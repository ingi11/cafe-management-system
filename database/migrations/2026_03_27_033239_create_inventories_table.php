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
    Schema::create('inventories', function (Blueprint $table) {
        $table->id();
        $table->string('item_name'); // e.g., Coffee Beans, Whole Milk
        $table->decimal('quantity', 10, 2); // Use decimal for kg/Liters
        $table->string('unit'); // e.g., kg, L, pcs
        $table->integer('min_stock_level')->default(5); // For dashboard alerts
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
