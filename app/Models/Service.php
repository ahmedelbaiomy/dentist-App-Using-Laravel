<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory,SoftDeletes;
    public $table = 'services';

    protected $fillable = ['service_name', 'price', 'note', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
