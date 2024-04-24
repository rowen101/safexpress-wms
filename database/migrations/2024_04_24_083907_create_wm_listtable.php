<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wm_listtable', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tablekey', 50)->nullable(false);
            $table->string('columnkey', 50)->nullable(false);
            $table->string('identitycode', 50)->nullable(false);
            $table->string('description', 100)->nullable(false);
            $table->integer('sortorder')->nullable(false)->default(0);
            $table->string('tracecode', 50)->nullable(true);
            $table->boolean('is_active')->default(true)->nullable(false);
            $table->string('created_by', 20)->nullable(true);
            $table->string('updated_by', 20)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wm_listtable');
    }
};
