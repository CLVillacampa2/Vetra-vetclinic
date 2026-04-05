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
    Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->string('patient_id')->unique();
        $table->string('name');
        $table->string('age');
        $table->string('birthday');
        $table->string('breed');
        $table->string('type');
        $table->boolean('is_young');
        $table->string('owner');
        $table->timestamps();
    });
}
};