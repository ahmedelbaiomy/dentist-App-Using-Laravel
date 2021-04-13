<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class note extends Model
{
    public $table = 'notes';
    protected $fillable = ['user_id', 'patient_id', 'note'];
}
