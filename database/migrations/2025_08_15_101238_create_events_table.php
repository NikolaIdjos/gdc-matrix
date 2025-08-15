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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id')->index();
            $table->foreign('league_id')
                ->references('id')
                ->on('leagues')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->dateTime('scheduled_at');
            $table->integer('status_id');
            $table->integer('competitor_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
