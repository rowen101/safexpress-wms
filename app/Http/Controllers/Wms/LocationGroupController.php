<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Models\Wms\Locationgroup;
use Illuminate\Http\Request;

class LocationGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = Locationgroup::query()
        ->orderBy('id', 'desc')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'warehouse_id' => $item->warehouse_id,
            'code' => $item->code,
            'description' => $item->description,
            'sortorder' => $item->sortorder,
            'is_active' => $item->is_active,
            'created_by' => $item->created_by,
            'created_at' => $item->created_at->format('m-d-Y'),
            'updated_by' => $item->updated_by,
            'updated_at' => $item->updated_at->format('m-d-Y'),
        ]);

        return $data;
    }

    public function store(Request $request)
    {
        $username = auth()->user()->name;
        $existingRecord = Locationgroup::where([
            'code' => $request->code,
            'description' => $request->description,

        ])->first();

        if ($existingRecord && !$request->id) {
            return response()->json(['message' => 'This data already exists in the database.'], 422);
        }

        $data = Locationgroup::updateOrCreate(
            ['id' => $request->id],
            [
                'warehouse_id' => 1,
                'code' => $request->code,
                'description' => $request->description,
                'locationtype' => $request->locationtype,
                'sortorder' => $request->sortorder,
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

    public function destroy($id)
    {
        $data = Locationgroup::find($id);
        $data->delete();
        return response()->json(['message' => 'successfull Deleted']);
    }
}
