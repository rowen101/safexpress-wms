<?php

namespace App\Models\Wms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseUser extends Model
{
    use HasFactory;

    protected $table = 'wm_warehouseuser';
    protected $fillable = ['id','warehouse_id','user_id','is_active','created_by','updated_by'];

    protected $casts = [
        'created_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
