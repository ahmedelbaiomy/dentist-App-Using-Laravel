<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service_category extends Model
{
    use HasFactory;
    public $table = 'service_categories';

    protected $fillable = ['parent_id', 'name'];
}
