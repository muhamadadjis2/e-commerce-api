<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Product::latest()->get();
        return response()->json([ProductResource::collection($datas), 'product fetched.']);
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
            'price' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name         = $request->name;
            $product->price        = $request->price;
            $product->transaction_id        = $request->transaction_id;

            $product->save();
            DB::commit();

            return response()->json(['product producted successfully.', new ProductResource($product)]);
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
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new productResource($product)]);
    }

    /**
     * Show the form for producting the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function product($id)
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
            'price' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'transaction_id' => $request->transaction_id
            ]);

       
            DB::commit();

            return response()->json(['product updated successfully.', new ProductResource($product)]);
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
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json('product deleted successfully');
    }
}
