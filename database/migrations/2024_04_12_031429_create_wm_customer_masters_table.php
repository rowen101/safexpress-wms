<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SebastianBergmann\Type\TrueType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wm_customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('wm_warehouse')->onDelete('cascade');
            $table->string('cuscode',20)->nullable(false);
            $table->string('cusname',100)->nullable(false);
            $table->integer('leadtime')->nullable(false);
            $table->integer('stockfreshness')->nullable(false);
            $table->boolean('is_active')->default(true)->nullable(false);
            $table->string('created_by',20)->nullable(false);
            $table->string('updated_by',20)->nullable(true);
            $table->timestamps();
        });

        Schema::create('wm_contactinfo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('wm_customer')->onDelete('cascade');
            $table->string('contactname',100)->nullable(false);
            $table->string('address1',50)->nullable(true);
            $table->string('address2',50)->nullable(true);
            $table->string('town',50)->nullable(true);
            $table->string('province',50)->nullable(true);
            $table->string('country',50)->nullable(true);
            $table->string('postal',10)->nullable(true);
            $table->string('cellphone',15)->nullable(true);
            $table->string('telephone',15)->nullable(true);
            $table->string('emailaddress',50)->nullable(true);
            $table->string('contacttype',10)->nullable(true);
            $table->string('route',20)->nullable(true);
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
        Schema::dropIfExists('wm_customer_masters');
    }
};
