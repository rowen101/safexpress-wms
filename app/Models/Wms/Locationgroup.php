<?php

namespace App\Models\Wms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locationgroup extends Model
{
    use HasFactory;
    protected $table = 'wm_location';
    protected $fillable = [
        'id',
        'warehouse_id',
        'loccode',
        'locationgroup',
        'locationtype',
        'stockfreshness',
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
