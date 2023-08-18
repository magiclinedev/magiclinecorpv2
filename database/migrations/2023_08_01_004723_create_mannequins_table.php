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
        Schema::create('mannequins', function (Blueprint $table) {
            $table->id();
            $table->string('po')->nullable();
            $table->string('itemref');
            $table->string('company');
            $table->string('category');
            $table->string('type');
            $table->string('price')->nullable();
            $table->longText('description')->nullable();
            $table->text('images')->nullable();
            $table->string('file')->nullable();
            $table->string('pdf')->nullable();
            $table->string('addedBy');
            $table->string('modifiedBy')->nullable();
            $table->string('activeStatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mannequins');
    }
};
