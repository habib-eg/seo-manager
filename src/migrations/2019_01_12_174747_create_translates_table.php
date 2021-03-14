<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('seo-manager.database.translates_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('route_id');
            $table->string('locale')->nullable();
            $table->string('url')->nullable();
            $table->text('keywords')->nullable();
            $table->string('description')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
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
        Schema::dropIfExists(config('seo-manager.database.translates_table'));
    }
}
