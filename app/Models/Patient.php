<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // This allows all fields to be saved to the database
    protected $guarded = [];

    // This connects the Patient to their Medical Records
    public function records() {
        return $this->hasMany(MedicalRecord::class);
    }
}