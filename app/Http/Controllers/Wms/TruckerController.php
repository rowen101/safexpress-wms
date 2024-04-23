<?php

namespace App\Http\Controllers\Wms;

use App\Models\Wms\Trucker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TruckerController extends Controller
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
        $data = Trucker::query()
        ->orderBy('id', 'desc')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'warehouse_id' => $item->warehouse_id,
            'truckercode' => $item->truckercode,
            'truckername' => $item->truckername,
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
        $existingRecord = Trucker::where([
            'truckercode' => $request->truckercode,
            'truckername' => $request->truckername,

        ])->first();

        if ($existingRecord && !$request->id) {
            return response()->json(['message' => 'This data already exists in the database.'], 422);
        }

        $data = Trucker::updateOrCreate(
            ['id' => $request->id],
            [
                'warehouse_id' => 1,
                'truckercode' => $request->truckercode,
                'truckername' => $request->truckername,
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
        $data = Trucker::find($id);
        $data->delete();
        return response()->json(['message' => 'successfull Deleted']);
    }
}
