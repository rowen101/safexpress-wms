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
        Schema::create('wm_uoms', function (Blueprint $table) {
            $table->id();
            $table->string('shortname',20)->nullable(false);
            $table->string('longname',50)->nullable(false);
            $table->string('uomtype',20)->nullable(false);
            $table->boolean('is_active')->default(true)->nullable(false);
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
        Schema::dropIfExists('wm_uoms');
    }
};
