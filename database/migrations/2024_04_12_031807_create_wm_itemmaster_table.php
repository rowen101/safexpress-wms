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
        Schema::create('wm_itemmaster', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('warehouse_id')->constrained('wm_warehouse')->onDelete('cascade');
            $table->string('itemcode', 30)->nullable(false);
            $table->string('referencecode', 30)->nullable(false);
            $table->string('description', 100)->nullable(false);
            $table->string('shortdesc', 30)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('handlingunit', 20)->nullable(false);
            $table->string('abccode', 20)->nullable(false);
            $table->decimal('unitcost', 18, 8)->nullable(false);
            $table->decimal('safestocklevel', 18, 8)->nullable(false);
            $table->integer('shelflife')->nullable(false);
            $table->char('shelflifeunit', 1)->nullable(false);
            $table->integer('salvagedays')->nullable(false);
            $table->char('stockrestriction', 1)->nullable(false);
            $table->string('lotformat', 20)->nullable(false);
            $table->char('lotformatdate', 1)->nullable(false);
            $table->string('batchfindstratagey', 10)->nullable(false);
            $table->integer('unitqtyperbatch')->nullable(false);
            $table->string('eachuom', 20)->nullable(false);
            $table->integer('eachqtyperhandlingunit')->nullable(false);
            $table->string('handlingunitbarcode', 20)->nullable(false);
            $table->string('eachbarcode', 20)->nullable(false);
            $table->string('unitcontentuom', 20)->nullable(false);
            $table->integer('unitcontentqty')->nullable();
            $table->decimal('unitvolume', 18, 8)->nullable(false);
            $table->decimal('unitweight', 18, 8)->nullable(false);
            $table->integer('minreplenishmentlvl')->nullable(false);
            $table->integer('maxreplenishmentqty')->nullable(false);
            $table->integer('eachreplenishmentlvl')->nullable(false);
            $table->integer('eachhureplenishmentqty')->nullable(false);
            $table->string('caselocation', 20)->nullable();
            $table->string('eachlocation', 20)->nullable();
            $table->boolean('isbatchmanaged')->default(true)->nullable(false);
            $table->boolean('is_active')->default(true)->nullable(false);
            $table->string('created_by', 20)->nullable();
            $table->string('updated_by', 20)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_item_masters');
    }
};
