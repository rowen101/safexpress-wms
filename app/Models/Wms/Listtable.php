<?php

namespace App\Models\Wms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listtable extends Model
{
    use HasFactory;

    protected $table = 'wm_listtable';
    protected $fillable = [
        'id',
        'tablekey',
        'columnkey',
        'identitycode',
        'description',
        'sortorder',
        'tracecode',
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
