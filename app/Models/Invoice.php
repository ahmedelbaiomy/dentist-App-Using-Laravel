<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $table = 'invoices';

    protected $fillable = ['code', 'from', 'to','type','total'];
}
