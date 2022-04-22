<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Validator;
use DB;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $datas = Transaction::with('products', 'payment_methods', 'customerAddress.customers')->get();
            
        return response()->json([
                'status' => 'OK',
                'results' => $datas
            ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'customer_address_id' => 'required|string|max:50',
            'code_transaction' => 'required',
            'transaction_date' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $transaction = new Transaction();
            $transaction->customer_address_id = $request->customer_address_id;
            $transaction->code_transaction    = $request->code_transaction;
            $transaction->transaction_date    = $request->transaction_date;
            $transaction->product_id    = $request->product_id;
            $transaction->payment_method_id    = $request->payment_method_id;

            $transaction->save();
            DB::commit();

            return response()->json(['Transaction added successfully.', new TransactionResource($transaction)]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message'   => 'DB Error',
                'debug'     => $e->getMessage()
            ], 500);
        }
    }
}
