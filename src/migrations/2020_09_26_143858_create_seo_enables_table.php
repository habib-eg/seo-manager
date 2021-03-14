<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoEnablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_enables', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('metatags')->nullable();
            $table->text('opengraph')->nullable();
            $table->text('json_ld')->nullable();
            $table->text('json_ld_multi')->nullable();
            $table->text('twitter')->nullable();
            $table->string('type')->nullable();
            $table->string('locale', 50)->default(app()->getLocale());
            $table->morphs('seotable');
            $table->index(["seotable_id", "seotable_type", "locale", "deleted_at"]);
            $table->softDeletesTz();
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
        Schema::dropIfExists('seo_enables');
    }
}

/**
 * Article
 * 'published_time' => 'datetime',
 * 'modified_time' => 'datetime',
 * 'expiration_time' => 'datetime',
 * 'author' => 'profile / array',
 * 'section' => 'string',
 * 'tag' => 'string / array'
 * ///////////////////////////////////////////////////////
 * Book
 * 'author' => 'profile / array',
 * 'isbn' => 'string',
 * 'release_date' => 'datetime',
 * 'tag' => 'string / array'
 * ///////////////////////////////////////////////////////
 * Profile
 * 'first_name' => 'string',
 * 'last_name' => 'string',
 * 'username' => 'string',
 * 'gender' => 'enum(male, female)'
 * ///////////////////////////////////////////////////////
 * music.album
 * 'duration' => 'integer',
 * 'album' => 'array',
 * 'album:disc' => 'integer',
 * 'album:track' => 'integer',
 * 'musician' => 'array'
 * ///////////////////////////////////////////////////////
 * music.playlis
 * 'song' => 'music.song',
 * 'song:disc' => 'integer',
 * 'song:track' => 'integer',
 * 'creator' => 'profile'
 *music.radio_station
 * 'creator' => 'profile'
 * ///////////////////////////////////////////////////////
 * video.movie
 * 'actor' => 'profile / array',
 * 'actor:role' => 'string',
 * 'director' => 'profile /array',
 * 'writer' => 'profile / array',
 * 'duration' => 'integer',
 * 'release_date' => 'datetime',
 * 'tag' => 'string / array'
 * ///////////////////////////////////////////////////////
 * video.episode
 * 'actor' => 'profile / array',
 * 'actor:role' => 'string',
 * 'director' => 'profile /array',
 * 'writer' => 'profile / array',
 * 'duration' => 'integer',
 * 'release_date' => 'datetime',
 * 'tag' => 'string / array',
 * 'series' => 'video.tv_show'
 * ///////////////////////////////////////////////////////
 * video.tv_show
 * 'actor' => 'profile / array',
 * 'actor:role' => 'string',
 * 'director' => 'profile /array',
 * 'writer' => 'profile / array',
 * 'duration' => 'integer',
 * 'release_date' => 'datetime',
 * 'tag' => 'string / array'
 * ///////////////////////////////////////////////////////
 * video.other
 * 'actor' => 'profile / array',
 * 'actor:role' => 'string',
 * 'director' => 'profile /array',
 * 'writer' => 'profile / array',
 * 'duration' => 'integer',
 * 'release_date' => 'datetime',
 * 'tag' => 'string / array'
 * ///////////////////////////////////////////////////////
 * video
 * 'url' => 'https://example.com/movie.swf',
 * 'secure_url' => 'https://example.com/movie.swf',
 * 'type' => 'application/x-shockwave-flash',
 * 'width' => 400,
 * 'height' => 300
 * ///////////////////////////////////////////////////////
 * audio
 * 'url' => 'https://example.com/movie.swf',
 * 'secure_url' => 'https://secure.example.com/sound.mp3',
 * 'type' => 'audio/mpeg'
 * place
 * 'location:latitude' => 'float',
 * 'location:longitude' => 'float',
 */
