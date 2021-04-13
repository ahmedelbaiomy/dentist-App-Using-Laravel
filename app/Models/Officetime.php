<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officetime extends Model
{
    use HasFactory;
    public $table = 'officetimes';

    protected $fillable = ['user_id', 'day', 'from', 'to'];
}
