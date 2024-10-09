<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\CustomerAddresse;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingCharge;
use Illuminate\Support\Carbon;
use App\Models\DiscountCoupon;






class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);

        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Produit non trouvé'
            ]);
        }

        if(Cart::count()  > 0){
          
         $cartContent = Cart::content();
         $productAlreadyExit = false;
         foreach ($cartContent as $item){
            if ($item->id == $product->id){
                $productAlreadyExit  = true;
            }

         }
               
         if($productAlreadyExit == false){
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty
            ($product->product_images)) ? $product->product_images->first() : '']);

            $status = true;
            $message =  $product->title.' Ajouter au Panier';
            session()->flash('success', $message);

         }else{

            $status = false; 
            $message = $product->title.' deja ajouter au Panier';

         }

        }else{

            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty
            ($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = $product->title.' Ajouter au panier';
            session()->flash('success', $message);

        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);

    }

    public function cart()
    {
        $cartContent = Cart::content();
        //dd($cartCount);

        $data ['cartContent'] = $cartContent;

        return view('front.cart',$data);
    }

    public function UpdateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;
        
        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        // check qty 

       if($product->track_qty == 'Yes')
        { 
            if($qty <= $product->qty)
            {
                Cart::update($rowId, $qty);
                $message = 'Mise a jour du panier';
                $status = true;
                session()->flash('success', $message);
            }else if($qty <= $product->qty){
                Cart::update($rowId, $qty);
                $message = 'Quantité disponible ('.$qty.') Stock Epuiser';
                $status = false;
                session()->flash('error', $message);
            }else{
                $message = 'Cette Quantité  n\'est pas disponible ('.$qty.') Stock Epuiser';
                $status = false;
                session()->flash('error', $message);
            }

        }else{

            Cart::update($rowId, $qty);
            $message = 'Mise a jour du panier';
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);

    }


    public function deleteItem(Request $request)
    {
        $itemInfo = Cart::get($request->rowId);

        if($itemInfo == null){

            session()->flash('error', 'Article non trouver');
            return response()->json([
                'status' => false,
                'message' => 'Article non trouver'
            ]);
        }

       Cart::remove($request->rowId); 

        session()->flash('success', 'Article Supprimer du Panier');
        return response()->json([
            'status' => true,
            'message' => 'Article supprimer du Panier'
        ]);

    }

    public function checkout()
    {

            $discount = 0;

            if(Cart::count() == 0){
                return  redirect()->route('front.cart');
            }

            if(Auth::check() == false){
                
                if(!session()->has('url.intended')){

                    session(['url.intended' => url()->current()]);
                }

                return  redirect()->route('account.login');
            }

            $customerAddress = CustomerAddresse::where('user_id',Auth::user()->id)->first();


            session()->forget('url.intended');

            $countries = Country::orderBy('name','ASC')->get();

            $shipp = ShippingCharge::where('country_id','rest_of_world')->get();

        
            //dd($shipp);

            $subTotal = Cart::subtotal(2,'.','');

            // apply discount here
            if(session()->has('code')){
                $code = session()->get('code');

                if($code->type == 'percent'){
                    $discount = ($code->discount_amount/100)*$subTotal;    
                }else{
                    $discount = $code->discount_amount;
                }

            }


        try
        {
            // calculate shipping here
            if($customerAddress != ''){

                $userCountry = $customerAddress->country_id;
                
                if($shipp  == 'rest_of_world'){
                    $shippingInfo = ShippingCharge::where('country_id',$userCountry)->first();
                }else{
                    $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
                }

                //dd($shippingInfo);
            
                $totalQty = 0;
                $totalShippingCharge = 0;  
                $grandTotal = 0;
                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }
    
                $totalShippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$totalShippingCharge;

                //dd($totalShippingCharge);

            }else{

                $grandTotal = ($subTotal-$discount);
                $totalShippingCharge = 0;
            }
            
        }catch (Exception $e) {

        }
            return view('front.checkout',[
                'countries' =>  $countries,
                'customerAddress' => $customerAddress,
                'totalShippingCharge' => $totalShippingCharge,
                'discount' => $discount,
                'grandTotal' => $grandTotal,
            ]);
    }


    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:10',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
       ]);

       if($validator->fails()){
         
            return response()->json([
                'status' => false,
                'message' => 's\'il vous plait corrigez l\'erreur',
                'errors' => $validator->errors()
            ]);      
       }

       //$customerAddress = CustomerAddresse::find();
           
         // customer store
         $user = Auth::user();
        CustomerAddresse::updateOrCreate(
             ['user_id' =>  $user->id],
             [
                'user_id' =>  $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'country_id' => $request->country,
                'address' => $request->address,
                'appartment' => $request->appartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'mobile' => $request->mobile
             ],

        );

           // customer Addresse order store
         if($request->payment_method == 'cod'){
            
            $discountCodeId = Null;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');


            if(session()->has('code')){
                $code = session()->get('code');
    
                if($code->type == 'percent'){
                    $discount = ($code->discount_amount/100)*$subTotal;    
                }else{
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode =  $code->code;
            }

           
            // calcul shippping
            $shippingInfo = ShippingCharge::where('country_id',$request->country)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if($shippingInfo != null){
                $shipping =  $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;
            }else{
                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
                $shipping =  $totalQty*$shippingInfo->amount;
                $grandTotal =($subTotal-$discount)+$shipping;
            }
           
            $order  = new Order();
            $order->user_id = $user->id;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'Not Paid';
            $order->status = 'Pending';
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->appartment = $request->appartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->mobile = $request->mobile;
            $order->notes = $request->order_notes;
            $order->save();

             // customer order item store

             foreach (Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

                // update stock product
               $productDate = Product::find($item->id);
                if($productDate->track_qty == 'Yes'){
                $currentQty = $productDate->qty;
                $updateQty = $currentQty-$item->qty;
                $productDate->qty = $updateQty;
                $productDate->save();
                }
             }

             // Send Email
            orderEmail($order->id, 'customer');

             session()->flash('success', 'Votre commande est enregistrer avec succes');

             Cart::destroy();

             session()->forget('code');

             return response()->json([
                'status' => true,
                'orderId' => $order->id,
                'message' => 'Commande valider avec Succes',
            ]);     


         }else{

         }
        
    }

    public function details($id)
    {
        return view('front.details',[
            'id' => $id,
        ]);
    }

    public function getOrderSummery(Request $request)
    {
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString = '';

        // apply discount here
        if(session()->has('code')){
            $code = session()->get('code');

            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;    
            }else{
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount-response">
            <strong>'.Session()->get('code')->code.'</strong>
            <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
             </div>';
        }


        if($request->country_id > 0){

            $shippingInfo = ShippingCharge::where('country_id',$request->country_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if($shippingInfo != null){
                
                $shippingCharge =  $totalQty*$shippingInfo->amount;

                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal,2),
                'discount' => number_format($discount,2),
                'discountString' =>  $discountString, 
                'shippingCharge' => number_format($shippingCharge,2),
                ]);

            }else{

                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();

                $shippingCharge =  $totalQty*$shippingInfo->amount;

                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal,2),
                'discount' => number_format($discount,2),
                'discountString' =>  $discountString,
                'shippingCharge' => number_format($shippingCharge,2),
                ]);
            }

        }
        else
        {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal-$discount),2),
                'discount' => number_format($discount,2),
                'discountString' =>  $discountString,
                'shippingCharge' => number_format(0,2),
                ]);

        }
    }

    public function applyDiscount(Request $request)
    {

      $code = DiscountCoupon::where('code',$request->code)->first();
      //dd($request->code);

        if($code == null){
            return response()->json([
                'status' => false,
                'message' => 'invalid Discount coupon0',
            ]);
        }

        // check if coupon start date is valid or not

        $now = Carbon::now();

        //echo $now->format('Y-m-d H:i:s');

        if($code->starts_at != ""){
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->starts_at);

            if($now->lt($startDate)){

                return response()->json([
                    'status' => false,
                    'message' => 'Coupon de réduction invalide1',
                ]);
            }
        }


        if($code->expires_at != ""){
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->expires_at);

            if($now->gt($endDate)){

                return response()->json([
                    'status' => false,
                    'message' => 'Coupon de réduction invalide2',
                ]);
            }
        }

        // max uses check coupon
        if($code->max_uses > 0 ){
            $couponUsed = Order::where('coupon_code_id',$code->id)->count();

            if($couponUsed >= $code->max_uses){
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon de réduction invalide3',
                ]);
            }
        }
        

        // max uses user check coupon
        if($code->max_uses_user > 0){
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();

            if($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status' => false,
                    'message' => 'vous avez déjà utilisez ce code promo',
                ]);
            }

        }

        $subTotal = Cart::subtotal(2,'.','');
        // Min amount condition
        if($code->min_amount > 0){
           if($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'message' => 'votre montant minimum doit être $'.$code->min_amount.'.',
                ]);

           }
        }
          

        session()->put('code', $code);

        return $this->getOrderSummery($request);
     
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
