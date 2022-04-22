<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\Models\PaymentMethod;
use App\Http\Resources\PaymentMethodResource;

class PaymentMethodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = PaymentMethod::latest()->get();
        return response()->json([PaymentMethodResource::collection($datas), 'PaymentMethod fetched.']);
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
            'name' => 'required|string|max:50',
            'is_active' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $paymentmethod = new PaymentMethod();
            $paymentmethod->name       = $request->name;
            $paymentmethod->is_active  = $request->is_active;
            $paymentmethod->transaction_id        = $request->transaction_id;

            $paymentmethod->save();
            DB::commit();

            return response()->json(['PaymentMethod added successfully.', new PaymentMethodResource($paymentmethod)]);
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
        $paymentmethod = PaymentMethod::find($id);
        if (is_null($paymentmethod)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new PaymentMethodResource($paymentmethod)]);
    }

    /**
     * Show the form for PaymentMethoding the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function PaymentMethod($id)
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
            'name' => 'required|string|max:50',
            'is_active' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $paymentmethod = PaymentMethod::findOrFail($id);
            $paymentmethod->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
                'transaction_id' => $request->transaction_id
            ]);

       
            DB::commit();

            return response()->json(['PaymentMethod updated successfully.', new PaymentMethodResource($paymentmethod)]);
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
    public function destroy(PaymentMethod $PaymentMethod)
    {
        $PaymentMethod->delete();

        return response()->json('PaymentMethod deleted successfully');
    }
}
