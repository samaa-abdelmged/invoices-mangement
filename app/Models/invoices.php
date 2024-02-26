<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoices extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted at'];

    public function section()
    {
        return $this->belongsTo(sections::class, 'section_id');
    }
}