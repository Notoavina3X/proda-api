<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            "success" => true,
            "message" => "Order list",
            "data" => $orders
        ]);
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'quantity' => 'required',
        ]);

        if($validator->stopOnFirstFailure()->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $order = Order::create($input);

        $product = Order::findOrFail($order->product_id)->product;
        $product->stock -= $order->quantity;
        $product->save();
        
        return response()->json([
            "success" => true,
            "message" => "Order created successfully.",
            "data" => $order
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }
        
        return response()->json([
            "success" => true,
            "message" => "Order retrieved successfully.",
            "data" => $order
        ]);
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
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json([
            "success" => true,
            "message" => "Order deleted successfully.",
            "data" => $order
        ]);
    }
}
