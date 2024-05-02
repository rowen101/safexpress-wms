<?php

namespace App\Http\Controllers\Wms;

use App\Models\Wms\Customer;
use Illuminate\Http\Request;
use App\Models\Wms\ContactInfo;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
    $data = Customer::query()
        ->join('wm_contactinfo', 'wm_customer.id', '=', 'wm_contactinfo.customer_id')
        ->select('wm_customer.*', 'wm_contactinfo.contactname',
        'wm_contactinfo.address1', 'wm_contactinfo.address2', 'wm_contactinfo.emailaddress',
        'wm_contactinfo.telephone', 'wm_contactinfo.cellphone','wm_contactinfo.town','wm_contactinfo.province',
        'wm_contactinfo.country','wm_contactinfo.postal')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'cuscode' => $item->cuscode,
            'cusname' => $item->cusname,
            'leadtime' => $item->leadtime,
            'stockfreshness' => $item->stockfreshness,
            'is_active' => $item->is_active,
            'created_by' => $item->created_by,
            'created_at' => $item->created_at->format('m-d-Y'),
            'updated_by' => $item->updated_by,
            'updated_at' => $item->updated_at->format('m-d-Y'),
            'contactname' => $item->contactname,
            'address1' => $item->address1,
            'address2' => $item->address2,
            'emailaddress' => $item->emailaddress,
            'cellphone' => $item->cellphone,
            'telephone' => $item->telephone,
            'town' => $item->town,
            'province' => $item->province,
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


        $existingRecord = Customer::where([
            'cuscode' => $request->cuscode,
            'cusname' => $request->cusname,
        ])->first();


        if ($existingRecord && !$request->id) {
            return response()->json(['message' => 'This data already exists in the database.'], 422);
        }


        DB::beginTransaction();

        try {

            $customer = Customer::updateOrCreate(
                ['id' => $request->id],
                [
                    'warehouse_id' => 1,
                    'cuscode' => $request->cuscode,
                    'cusname' => $request->cusname,
                    'leadtime' => $request->leadtime,
                    'stockfreshness' => $request->stockfreshness,
                    'is_active' => $request->is_active,
                    'created_by' => $username
                ]
            );


           ContactInfo::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'contactname' => $request->contactname,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'town' => $request->town,
                    'province' => $request->province,
                    'country' => $request->country,
                    'postal' => $request->postal,
                    'telephone' => $request->telephone,
                    'cellphone' => $request->cellphone,
                    'emailaddress' => $request->emailaddress,
                    'created_by' => $username
                ]
            );


            DB::commit();


            $message = $customer->wasRecentlyCreated ? 'saved successfully.' : 'updated successfully.';

            return response()->json(['message' => $message]);
        } catch (\Exception $e) {

            DB::rollback();


            return response()->json(['message' => 'Error occurred while saving data.'], 500);
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
        $data = Customer::find($id);
        $data->delete();

        $datainfo = Contactinfo::where('customer_id',$id);
        $datainfo->delete();
        return response()->json(['message' => 'successfull Deleted']);
    }
}
