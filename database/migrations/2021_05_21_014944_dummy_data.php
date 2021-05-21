<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DummyData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('dummy_data', function (Blueprint $table){
        $table->bigIncrements('id');
        $table->string('sender');
        $table->string('receiver');
        $table->string('message')->nullable();
        $table->boolean('starred');
        $table->boolean('important');
        $table->boolean('read');
        $table->json('labels')->nullable();
        $table->boolean('has_attachment');
        $table->string('attachment_link')->nullable();
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
      Schema::dropIfExists('dummy_data');
    }
}
