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
        Schema::create('wm_location', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('wm_warehouse')->onDelete('cascade');
            $table->string('loccode',20)->nullable(false);
            $table->string('locationgroup',20)->nullable(false);
            $table->string('locationtype',20)->nullable(false);
            $table->string('abccode',20)->nullable(false);
            $table->string('description',50)->nullable(false);
            $table->integer('batchcapacity')->nullable(false);
            $table->boolean('is_active')->default(true)->nullable(true);
            $table->boolean('is_lockin')->default(true)->nullable(true);
            $table->boolean('is_lockout')->default(true)->nullable(true);
            $table->boolean('is_overflow')->default(true)->nullable(true);
            $table->boolean('is_vertual')->default(true)->nullable(true);
            $table->string('drivezone',20)->nullable(false);
            $table->integer('putawayorder')->nullable(false);
            $table->string('created_by',20)->nullable(false);
            $table->string('updated_by',20)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_storage_locations');
    }
};
