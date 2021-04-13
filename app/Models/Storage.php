<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;
    public $table = 'patient_storage';

    protected $fillable = ['patient_id', 'title', 'description', 'url'];
}
