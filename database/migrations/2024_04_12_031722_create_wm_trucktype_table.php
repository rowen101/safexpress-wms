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
        Schema::create('wm_trucktype', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('wm_warehouse')->onDelete('cascade');
            $table->string('typecode',20);
            $table->string('description',50);
            $table->decimal('handlingweight',18, 8)->nullable();
            $table->decimal('handlingvolume', 18, 8)->nullable();
            $table->integer('sortorder');
            $table->boolean('is_active')->default(true)->nullable(false);
            $table->string('created_by',20);
            $table->string('updated_by',20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_truck_types');
    }
};
