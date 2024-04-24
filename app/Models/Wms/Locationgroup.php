<?php

namespace App\Models\Wms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locationgroup extends Model
{
    use HasFactory;
    protected $table = 'wm_locationgroup';
    protected $fillable = [
        'id',
        'warehouse_id',
        'code',
        'description',
        'sortorder',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
