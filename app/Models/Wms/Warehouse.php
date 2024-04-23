<?php

namespace App\Models\Wms;

use App\Models\Wms\WarehouseUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{


    protected $table = 'wm_warehouse';

    protected $fillable = ['id','code','description','is_active','created_by','updated_by'];

    public function warehouseuser()
    {
        return $this->hasMany(WarehouseUser::class, 'warehouse_id', 'id');
    }

    public function custeromers()
    {
        return $this->hasMany(Customer::class, 'warehouse_id', 'id');
    }
    protected $casts = [
        'created_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
