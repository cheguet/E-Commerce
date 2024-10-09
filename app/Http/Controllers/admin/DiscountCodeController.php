<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\DiscountCoupon;



class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $discountCoupons  = DiscountCoupon::latest();

        if(!empty($request->get('keyword'))){
            $discountCoupons = $discountCoupons->where('name','like','%'.$request->get('keyword').'%');
          } 

          $discountCoupons = $discountCoupons->paginate(10);

         return view('admin.coupon.list',compact('discountCoupons'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes())
        {
            //must greator than current date
            if(!empty($request->starts_at)){
               $now = Carbon::now();
              $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
            }

            if($startAt->lte($now) == true){
                return response()->json([
                    'status' => false,
                    'errors' => ['starts_at' => 'start date can not be less than current data time']
                 ]);
            }


            if(!empty($request->starts_at) && !empty($request->expires_at)){
               $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
               $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
 
                if($expiresAt->gt($startAt) == false){
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'expiry date must be greator than star date']
                    ]);
                }  
            }

            $discountCoupon = new DiscountCoupon();
            $discountCoupon->code = $request->code;
            $discountCoupon->name = $request->name;
            $discountCoupon->description = $request->description;
            $discountCoupon->max_uses = $request->max_uses;
            $discountCoupon->max_uses_user = $request->max_uses_user;
            $discountCoupon->type = $request->type;
            $discountCoupon->discount_amount = $request->discount_amount;
            $discountCoupon->min_amount = $request->min_amount;
            $discountCoupon->status = $request->status;
            $discountCoupon->starts_at = $request->starts_at;
            $discountCoupon->expires_at = $request->expires_at;
            $discountCoupon->save();

            $request->session()->flash('success','Coupon added successfully');

              return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
             ]);

        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
             ]);
        }


    }

    public function edit($id, Request $request)
    {
        $discountCoupon = DiscountCoupon::find($id);

        if(empty($discountCoupon)){
          return redirect()->route('coupons.index');
        }
        return view('admin.coupon.edit',compact('discountCoupon'));
    }

    public function update($id ,Request $request)
    {

        $discountCoupon = DiscountCoupon::find($id);


        if(empty($discountCoupon)){
        
            $request->session()->flash('error','Coupon  not found');
    
            return redirect()->json([
              'status' => false,
              'NotFound' => true,
              'message' => 'Coupon Not Found'
            ]);
          }

        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes())
        {
            //must greator than current date
            if(!empty($request->starts_at)){
               $now = Carbon::now();
              $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
            }

            if($startAt->lte($now) == true){
                return response()->json([
                    'status' => false,
                    'errors' => ['starts_at' => 'start date can not be less than current data time']
                 ]);
            }


            if(!empty($request->starts_at) && !empty($request->expires_at)){
               $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
               $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
 
                if($expiresAt->gt($startAt) == false){
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'expiry date must be greator than star date']
                    ]);
                }  
            }


            $discountCoupon->code = $request->code;
            $discountCoupon->name = $request->name;
            $discountCoupon->description = $request->description;
            $discountCoupon->max_uses = $request->max_uses;
            $discountCoupon->max_uses_user = $request->max_uses_user;
            $discountCoupon->type = $request->type;
            $discountCoupon->discount_amount = $request->discount_amount;
            $discountCoupon->min_amount = $request->min_amount;
            $discountCoupon->status = $request->status;
            $discountCoupon->starts_at = $request->starts_at;
            $discountCoupon->expires_at = $request->expires_at;
            $discountCoupon->save();

            $request->session()->flash('success','Coupon updated successfully');

              return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
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
        
       $discountCoupon = DiscountCoupon::find($id);

        if(empty($discountCoupon)){
            $request->session()->flash('error','Coupon not found');
            return response()->json([
            'status' => true,
            'message' => 'Coupon  not found'
             ]);
        }

        $discountCoupon->delete();
        $request->session()->flash('success','Coupon deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Coupon deleted successfully'
        ]);

    }
}
