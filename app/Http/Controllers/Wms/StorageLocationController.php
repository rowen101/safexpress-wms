<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Models\Wms\StorageLocation;
use Illuminate\Http\Request;

class StorageLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = StorageLocation::query()
        ->orderBy('id', 'desc')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'warehouse_id' => $item->warehouse_id,
            'loccode' => $item->loccode,
            'locationgroup' => $item->locationgroup,
            'locationtype' => $item->locationtype,
            'abccode' => $item->abccode,
            'description' => $item->description,
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
        $username = auth()->user()->name;
        $existingRecord = StorageLocation::where([
            'loccode' => $request->loccode,
            'locationgroup' => $request->locationgroup,

        ])->first();

        if ($existingRecord && !$request->id) {
            return response()->json(['message' => 'This data already exists in the database.'], 422);
        }

        $data = StorageLocation::updateOrCreate(
            ['id' => $request->id],
            [
                'warehouse_id' => 1,
                'loccode' => $request->loccode,
                'locationgroup' => $request->locationgroup,
                'locationtype' => $request->locationtype,
                'abccode' => $request->abccode,
                'description' => $request->description,
                'is_active' => $request->is_active,
                'created_by' => $username
            ]
        );

        if ($data->wasRecentlyCreated) {
            return response()->json(['message' => 'saved successfully.']);
        } else {
            return response()->json(['message' => 'updated successfully.']);
        }
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
        $data = StorageLocation::find($id);
        $data->delete();
        return response()->json(['message' => 'successfull Deleted']);
    }
}
