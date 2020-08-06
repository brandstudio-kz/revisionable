<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use BrandStudio\Revisionable\Revision;

class CreateRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('revisionable_description');
            $table->unsignedBigInteger('revisionable_id');
            $table->string('revisionable_type');

            $table->string('responsible_description');
            $table->unsignedBigInteger('responsible_id')->nullable();
            $table->string('responsible_type');

            $table->tinyInteger('action')->default(Revision::UPDATED);

            $table->boolean('highlight')->default(false);

            $table->json('old')->nullable();
            $table->json('new')->nullable();

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
        Schema::dropIfExists('revisions');
    }
}
