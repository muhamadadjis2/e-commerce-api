<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Customer::latest()->get();
        return response()->json([CustomerResource::collection($datas), 'customers fetched.']);
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
        $validator = Validator::make($request->all(),[
            'customer_name' => 'string|max:50',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try{
            
        $customer = Customer::create([
            'customer_name' => $request->customer_name,
            'customer_address_id' => $request->customer_address_id
         ]);
        
        return response()->json(['Customer created successfully.', new CustomerResource($customer)]);
        
        } catch (\Exception $e) {
            DB::rollback()->json([
                'message' => 'DB Error',
                'debug'   => $e->getMesssage()
            ],500);
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
        $customer = Customer::find($id);
        if (is_null($customer)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new CustomerResource($customer)]);
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

        $validator = Validator::make($request->all(),[
            'customer_name' => 'required|string|max:50',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($id);
            $customer->update([
                'customer_name' => $request->customer_name
            ]);

       
            DB::commit();

            return response()->json(['customer updated successfully.', new CustomerResource($customer)]);
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
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json('customer deleted successfully');
    }
}
