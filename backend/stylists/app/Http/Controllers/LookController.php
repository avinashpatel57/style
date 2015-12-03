<?php

namespace App\Http\Controllers;

use App\Look;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action)
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        return $this->$method($_SERVER['REQUEST_METHOD'] == 'POST' ? $request : null);
    }

    public function getList(){
        $looks = Look::where('look_id','<=',8000)->get()->slice(0,10)->all();
        return view('look.list',['looks'=> $looks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        echo "getCreate";
    }

    /**
     * Store a newly created look in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreate(Request $request)
    {

        $look = new Look();
        $look->look_name = isset($request->look_name) && $request->look_name != '' ? $request->look_name : '';
        $look->look_description = isset($request->look_description) && $request->look_description != '' ? $request->look_description : '';
        $look->body_type = isset($request->body_type) && $request->body_type != '' ? $request->body_type : '';
        $look->budget = isset($request->budget) && $request->budget != '' ? $request->budget : '';
        $look->age = isset($request->age) && $request->age != '' ? $request->age : '';
        $look->occasion = isset($request->occasion) && $request->occasion != '' ? $request->occasion : '';
        $look->gender = isset($request->gender) && $request->gender != '' ? $request->gender : '';
        $look->stylish_id = isset($request->stylish_id) && $request->stylish_id != '' ? $request->stylish_id : '';
        $look->date = date('Y-m-d H:i:s');

        $look_price = 0;

        if(isset($request->product_id1) && $request->product_id1 != ''){
            $look->product_id1 = $request->product_id1;
            $product1 = Product::find($request->product_id1);
            $look_price += $product1->product_price;
        }
        else
            $look->product_id1 = '';

        if(isset($request->product_id2) && $request->product_id2 != ''){
            $look->product_id2 = $request->product_id2;
            $product2 = Product::find($request->product_id2);
            $look_price += $product2->product_price;
        }
        else
            $look->product_id2 = '';

        if(isset($request->product_id3) && $request->product_id3 != ''){
            $look->product_id3 = $request->product_id3;
            $product3 = Product::find($request->product_id3);
            $look_price += $product3->product_price;
        }
        else
            $look->product_id3 = '';

        if(isset($request->product_id4) && $request->product_id4 != ''){
            $look->product_id4 = $request->product_id4;
            $product4 = Product::find($request->product_id4);
            $look_price += $product4->product_price;
        }
        else
            $look->product_id4 = '';

        $look->lookprice = $look_price;

        if($look->save()){
            return response()->json(array('success' => true, 'look_id' => $look->id), 200);
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
        //
    }
}
