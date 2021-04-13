<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    public $table = 'patients';

    protected $fillable = ['name', 'email', 'birthday', 'address', 'phone', 'state'];
    
}
