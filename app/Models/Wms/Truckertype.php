<?php

namespace App\Models\Wms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truckertype extends Model
{
    use HasFactory;

    protected $table = 'wm_trucktype';
    protected $fillable = ['id','warehouse_id','typecode','decription','handlingweight','sortorder','is_active','created_by','updated_by'];
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
