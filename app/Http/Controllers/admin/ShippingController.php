<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Validator;


class ShippingController extends Controller
{

    public function index(Request $request)
    {
        $shippingCharges = ShippingCharge::select('shipping_charges.*','countries.name')
        ->leftJoin('countries','countries.id','shipping_charges.country_id')
        ->latest();
        
        if(!empty($request->get('keyword'))){
            $shippingCharges = $shippingCharges->where('name','like','%'.$request->get('keyword').'%');
        }
        $shippingCharges = $shippingCharges->paginate(10);
        return view('admin.shipping.list',compact('shippingCharges'));
        
    }


    public function create()
    {
        $countries = Country::get();
        $data['countries'] = $countries;

        $shippingCharges = ShippingCharge::select('shipping_charges.*','countries.name')
        ->leftJoin('countries','countries.id','shipping_charges.country_id')->get();
        $data['shippingCharges'] = $shippingCharges;

        return view('admin.shipping.create',$data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->passes())
        {
            $count = ShippingCharge::where('country_id',$request->country)->count();
            if($count > 0){
                $request->session()->flash('error','Shipping already added');
                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

             $request->session()->flash('success','Shipping added successfully');
              return response()->json([
                'status' => true,
             ]);
        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
             ]);
        }
             
    }

    public function edit($id)
    {   
        $shippingCharge = ShippingCharge::find($id);

        $countries = Country::get();
        $data['countries'] = $countries;
        $data['shippingCharge'] = $shippingCharge;


        return view('admin.shipping.edit',$data);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->passes())
        {
            $shipping = ShippingCharge::find($id);
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

             $request->session()->flash('success','Shipping update successfully');
              return response()->json([
                'status' => true,
             ]);
        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
             ]);
        }
             
    }

    public function destroy($id, Request $request)
    {
        $shippingCharge = ShippingCharge::find($id);

        if(empty($shippingCharge)){

            $request->session()->flash('error','Shipping Not found');
            return response()->json([
                'status' => true,
             ]);
        }

        $shippingCharge->delete();

        $request->session()->flash('success','Shipping delete successfully');
        return response()->json([
            'status' => true
        ]);

    }
    
}
