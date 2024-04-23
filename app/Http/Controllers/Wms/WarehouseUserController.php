<?php

namespace App\Http\Controllers\Wms;

use App\Models\Wms\Warehouse;
use Illuminate\Http\Request;
use App\Models\Wms\WarehouseUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WarehouseUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
{
    $user_id = $request->input('user_id');
    $sites = Warehouse::select('wm_warehouse.*', 'wm_warehouseuser.is_active as user_is_active')
        ->leftJoin('wm_warehouseuser', function ($join) use ($user_id) {
            $join->on('wm_warehouseuser.warehouse_id', '=', 'wm_warehouse.id')
                ->where('wm_warehouseuser.user_id', '=', $user_id);
        })
        ->where('wm_warehouse.is_active', 1)
        ->orderBy('wm_warehouse.id', 'ASC')
        ->get();

    $sites->each(function ($sitesItem) {
        if ($sitesItem->wm_warehouseuser && $sitesItem->wm_warehouseuser->user_is_active == 1) {
            $sitesItem->is_active = true;
        } else {
            $sitesItem->is_active = false; // If wm_warehouseuser is null or is_active is not 1, consider it as inactive
        }
    });

    return response()->json($sites);
}




    public function getsitewthuserid()
    {
        Warehouse::where('is_active', 1)
            ->orderBy('code', 'ASC')
            ->get();

        return response()->json();
    }

    public function onSaveupdate(Request $request)
    {
        $user = auth()->user();
        $username = $user->name;
        try {
            // Begin transaction
            DB::beginTransaction();

            // Extract user_id and menu_ids from the request
            $user_id = $request->input('user_id');
            $site_ids = $request->input('site_id');

            // Delete existing user-menu relationships for the specified user_id
            WarehouseUser::where('user_id', $user_id)->delete();

            // Prepare the data array for bulk insert
            $data = [];
            foreach ($site_ids as $site_id) {
                $data[] = ['user_id' => $user_id, 'warehouse_id' => $site_id,'is_active' => true, 'created_by' => $username];
            }

            // Bulk insert the new user-menu relationships
            WarehouseUser::insert($data);

            // Commit transaction
            DB::commit();

            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Exception $e) {
            // Rollback transaction on failure
            DB::rollback();

            // Handle the exception, you can log it or return an error response
            return response()->json(['message' => 'Failed to save data', 'error' => $e->getMessage()], 500);
        }
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
