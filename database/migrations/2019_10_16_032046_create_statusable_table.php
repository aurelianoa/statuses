<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusableTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('statch-statuses.tables.statusables'), function (Blueprint $table) {
            $table->unsignedBigInteger('status_id');
            $table->morphs('statusable');
            $table->timestamps();

            // Indexes
            $table->unique(['status_id', 'statusable_id', 'statusable_type'], 'statusables_ids_type_unique');
            $table->foreign('status_id')->references('id')->on(config('statch-statuses.tables.statuses'))->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('statch-statuses.tables.statusables'));
    }
}
