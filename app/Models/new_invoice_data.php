<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class new_invoice_data extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    public $table = 'new_invoice_table';
    //log
    protected static $logName = 'New item';
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = false;

    // public function sprequest()
    // {
    //     return $this->belongsTo(Sprequest::class,'request_id');
    // }
    
}
