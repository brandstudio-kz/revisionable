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

            $table->unsignedBigInteger('model_id');

            $table->unsignedBigInteger('responsible_id')->nullable();

            $table->string('model');
            $table->tinyInteger('action')->default(Revision::UPDATED);

            $table->json('old')->nullable();
            $table->json('new')->nullable();

            // $table->json('from')->nullable();
            // $table->json('to')->nullable();
            //
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
