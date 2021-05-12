<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoicepayment extends Model
{
    use HasFactory,SoftDeletes;
    public $table = 'inv_invoice_payments';

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }
}
