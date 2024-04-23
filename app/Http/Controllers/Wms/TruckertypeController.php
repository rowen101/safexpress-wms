<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Models\Wms\Truckertype;
use Illuminate\Http\Request;

class TruckertypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Truckertype::query()
        ->orderBy('id', 'desc')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'warehouse_id' => $item->warehouse_id,
            'trypecode' => $item->trypecode,
            'description' => $item->description,
            'handlingweight' => $item->handlingweight,
            'hanglingvolume' => $item->hanglingvolume,
            'is_active' => $item->is_active,
            'created_by' => $item->created_by,
            'created_at' => $item->created_at->format('m-d-Y'),
            'updated_by' => $item->updated_by,
            'updated_at' => $item->updated_at->format('m-d-Y'),
        ]);

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
