<?php

namespace App\Models\Wms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    use HasFactory;
    protected $table = 'wm_location';
    protected $fillable = [
        'id',
        'warehouse_id',
        'loccode',
        'locationgroup',
        'locationtype',
        'abccode',
        'description',
        'batchcapacity',
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
