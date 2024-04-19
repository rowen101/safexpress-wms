<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;
    protected $table = 'wm_contactinfo';
    protected $fillable = ['customer_id','contactname','address1','address2','town','province','country','postal','cellphone','telephone','emailaddress','contacttype','route','created_by','updated_by'];

}
