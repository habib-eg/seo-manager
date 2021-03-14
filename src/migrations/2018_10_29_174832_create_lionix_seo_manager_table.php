<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLionixSeoManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('seo-manager.database.table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('uri');
            $table->text('params')->nullable();
            $table->text('mapping')->nullable();
            $table->text('keywords')->nullable();
            $table->string('description')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->string('url')->nullable();
            $table->text('title_dynamic')->nullable();
            $table->text('og_data')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('seo-manager.database.table'));
    }
}
