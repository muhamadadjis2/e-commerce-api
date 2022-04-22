<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\Models\CustomerAddress;
use App\Http\Resources\CustomerAddressResource;

class CustomerAddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = CustomerAddress::with('customers')->get();
        
        return response()->json([
            'status' => 'OK',
            'results' => $datas
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'customer_id' => 'required|string|max:50',
            'address' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $customeraddress = new CustomerAddress();
            $customeraddress->customer_id  = $request->customer_id;
            $customeraddress->address  = $request->address;

            $customeraddress->save();
            DB::commit();

            return response()->json(['CustomerAddress added successfully.', new CustomerAddressResource($customeraddress)]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message'   => 'DB Error',
                'debug'     => $e->getMessage()
            ], 500);
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
        $customeraddress = CustomerAddress::find($id);
        if (is_null($customeraddress)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new CustomerAddressResource($customeraddress)]);
    }

    /**
     * Show the form for CustomerAddressing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'customer_id' => 'required|string|max:50',
            'address' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $customeraddress = CustomerAddress::findOrFail($id);
            $customeraddress->update([
                'customer_id' => $request->customer_id,
                'address' => $request->address
            ]);

       
            DB::commit();

            return response()->json(['CustomerAddress updated successfully.', new CustomerAddressResource($customeraddress)]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message'   => 'DB Error',
                'debug'     => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerAddress $CustomerAddress)
    {
        $CustomerAddress->delete();

        return response()->json('CustomerAddress deleted successfully');
    }
}
