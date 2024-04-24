<?php

namespace App\Models\Wms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;
    protected $table = 'wm_contactinfo';
    protected $fillable = ['customer_id','contactname','address1','address2','town','province','country','postal','cellphone','telephone','emailaddress','created_by','updated_by'];

}
