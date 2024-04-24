<?php

namespace App\Http\Controllers\Wms;

use Illuminate\Http\Request;
use App\Models\Wms\Listtable;
use App\Http\Controllers\Controller;

class ListtableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getkey($key)
    {
        $data = Listtable::where('tablekey',$key)
        ->orderBy('id', 'desc')
        ->where('is_active', true)
        ->get();

        return $data;
    }
}
