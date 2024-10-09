<?php
use App\Models\Category;
use App\Models\Order;
use App\Models\Country;
use App\Models\Page;
use App\Models\ProductImage;
use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;


    function getCategories()
    {
       return  Category::orderBy('name','ASC')
       ->with('sub_category')
       ->orderBy('id','DESC')
       ->where('status',1)
       ->where('showHome','Yes')
       ->get();
    }

    function getProductImage($productId)
    {
        return ProductImage::where('product_id',$productId)->first();
    }

    function orderEmail($orderId, $userType="customer")
    {
        $order = Order::where('id',$orderId)->with('items')->first();

        if($userType == 'customer')
        {
            $subject = 'Merci pour votre commande';
            $email = $order->email;
        }else{
           $subject = 'Vous avez reçu sur commande';
           $email = env('ADMIN_EMAIL');
        }

        $mailData = [
             'subject' => $subject,
             'order'   => $order,
             'userType' => $userType,
        ];

        Mail::to($email)->send(new EmailConfirmation($mailData));
       // dd($order);
    }

    function getCountryInfo($id)
    {
        return Country::where('id',$id)->first();
    }

    function staticPages()
    {
        $page = Page::orderBy('name','ASC')->get();
        
        return $page;
    }
?>