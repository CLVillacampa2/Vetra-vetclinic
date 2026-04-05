<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->integer('stock');
            $table->string('unit');
            $table->string('threshold');
            $table->string('supplier');
            $table->string('restocked');
            $table->boolean('critical');
            $table->timestamps();
        });
    }
};
