<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    // This allows all fields to be saved to the database
    protected $guarded = [];

    // This connects the Medical Record back to the Patient
    public function patient() {
        return $this->belongsTo(Patient::class);
    }
}