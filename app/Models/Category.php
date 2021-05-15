<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    public $table = 'service_categories';

    protected $fillable = ['parent_id', 'name'];

    public function services()
    {
        return $this->hasMany(Service::class,'category_id');
    }
}
