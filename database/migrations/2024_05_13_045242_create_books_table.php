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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->integer('surfing_experience')->default(0);
            $table->date('visit_date')->nullable();
            $table->string('desired_board')->nullable();
            $table->text('file_id_verification')->nullable();
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
