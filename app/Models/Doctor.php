<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    public $table = 'doctors';

    protected $fillable = ['user_id', 'birthday', 'address', 'phone', 'photo', 'target'];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
