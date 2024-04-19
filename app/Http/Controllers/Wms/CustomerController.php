<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
        $data = Customer::all();
        return response()->json($data);
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
        $existingRecord = Customer::where([
            'cuscode' => $request->cuscode,
            'cusname' => $request->cusname,
            'leadtime' => $request->leadtime,
            'stockfreshness' => $request->stockfreshness,
            'is_active' => $request->is_active,
        ])->first();

        if ($existingRecord && !$request->id) {
            return response()->json(['message' => 'This data is already in the database.'], 422);
        }
        Customer::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'warehouse_id' => 1,
                'cuscode' => $request->cuscode,
                'cusname' => $request->cusname,
                'leadtime' => $request->leadtime,
                'stockfreshness' => $request->stockfreshness,
                'is_active' => true,
                'created_by'=>$username

            ]
        );

        return response()->json(['message' => 'success']);
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
