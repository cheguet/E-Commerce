<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Stripe;
use App\Models\ShippingCharge;
use App\Models\CustomerAddresse;
use Illuminate\Support\Facades\Validator;






class StripeController extends Controller
{
    public function session(Request $request) 
    {

       $user = Auth::user();

       \Stripe\Stripe::setApiKey(config('stripe.sk')); 

       $numerocommande = $request->get('numerocommande');
       $total = str_replace([',', '.'], ['', ''], $request->get('total'));

       $grandTotal = $total;

        $checkoutSession = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' =>  $numerocommande,
                        ],
                        'currency'  =>  'eur',
                        'unit_amount' => $grandTotal,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode'  => 'payment',
            'allow_promotion_codes'=> true,
            'customer_email'  => $user->email,
             'success_url'  => route('front.success'),
            'cancel_url'  => route('front.cancel'), 
        ]);

        return redirect()->away($checkoutSession->url);

    }


    public function success()
    {
        if(Auth::check() == false){
           return  redirect()->route('account.login');
        }else{
            return view('front.success');
        }  
    }

    public function cancel()
    {
        if(Auth::check() == false){
            return  redirect()->route('account.login');
         }else{
            return view('front.cancel');
         }
    }
}
