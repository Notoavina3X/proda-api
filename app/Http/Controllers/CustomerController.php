<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json([
            "success" => true,
            "message" => "Customer list",
            "data" => $customers
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
            'name' => 'required',
            'email' => ['required', 'email', 'unique:customers']
        ]);

        if($validator->stopOnFirstFailure()->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $customer = Customer::create($input);
        

        return response()->json([
            "success" => true,
            "message" => "Customer created successfully.",
            "data" => $customer
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
        $customer = Customer::findOrFail($id);
        if (is_null($customer)) {
            return $this->sendError('Customer not found.');
        }
        $orders = Customer::find($id)->orders;
        return response()->json([
            "success" => true,
            "message" => "Customer retrieved successfully.",
            "data" => [
                'customer' => $customer,
                'customer_orders' => $orders
            ]
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:customers']
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $customer = Customer::findOrFail($id);
        $customer->update($input);

        return response()->json([
            "success" => true,
            "message" => "Customer updated successfully.",
            "data" => $customer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json([
            "success" => true,
            "message" => "Customer deleted successfully.",
            "data" => $customer
        ]);
    }
}
