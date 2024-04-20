<?php

namespace App\Http\Controllers\Wms;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Warehouse::query()
        ->orderBy('id', 'desc')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'code' => $item->code,
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
        $user = auth()->user();
        $username = $user->name;

        if ($request->id) {

            $warehouse = Warehouse::find($request->id);
            if ($warehouse) {
                $warehouse->update([
                    'code' => $request->code,
                    'description' => $request->description,
                    'is_active' => $request->is_active,
                    'updated_by' => $username,
                    'updated_at' => now()
                ]);
                return response()->json(['message' => 'Update success!']);
            } else {
                return response()->json(['message' => 'Warehouse not found.']);
            }
        } else {

            Warehouse::create([
                'code' => $request->code,
                'description' => $request->description,
                'is_active' => $request->is_active,
                'created_by' => $username
            ]);
            return response()->json(['message' => 'Create success!']);
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
     Warehouse::find($id);
      return response()->json(['message' => 'success']);
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
        $data = Warehouse::find($id);
        $data->delete();
        return response()->json(['message' => 'Warehouse successfull Deleted']);
    }

    public function getAuthWarehouse(Request $request)
    {
        $email = $request->input('email');
        $users = User::where('email', $email)
        ->first();
        $userId =$users->id;

        $sites = Warehouse::select('wm_warehouse.*')
            ->join('wm_warehouseuser', 'wm_warehouse.id', '=', 'wm_warehouseuser.warehouse_id')
            ->where('wm_warehouse.is_active', 1)
            ->where('wm_warehouseuser.user_id', '=', $userId)
            ->orderBy('wm_warehouse.description', 'ASC')
            ->get();

        return response()->json(['message' => 'success', 'sites' => $sites]);
    }
}
