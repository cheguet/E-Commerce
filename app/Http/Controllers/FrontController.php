<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Page;
use App\Models\User;
use App\Models\WishList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactEmail;
use Illuminate\Support\Facades\Mail;






use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
       $populaireproducts = Product::where('is_featured','Yes')
       ->orderBy('id','DESC')
       ->where('status',1)
       ->take(8)->get();

       $data['populaireproducts'] = $populaireproducts;

       $dernierproducts = Product::orderBy('id','DESC')
       ->where('status',1)
       ->take(8)
       ->get();
       
       $data['dernierproducts'] = $dernierproducts;

        return view('front.home',$data);
    }

    public function addToWishlist(Request $request)
    {
        if(Auth::check() == false){
          
            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false,
            ]);
        }

        $product = Product::where('id',$request->id)->first();
          
        if($product == null){

            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Produit introvable</div>',
            ]);
        }
         
             WishList::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->id,
                ],
                [
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->id,
                ]
             );


        /* $wishlist = new  WishList;
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $request->id;
        $wishlist->save(); 
       */
     
        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'" </strong>ajouter dans votre liste de souhaits</div>',
        ]);

    }


    public function page($slug)
    {
        $page = Page::where('slug',$slug)->first();
        if($page == null){
            abort(404);
        }
        return view('front.page',[
            'page' => $page,
        ]);
    }

    public function sendContactEmail(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        if($validator->passes()){

            $mailData = [
              'name' => $request->name,
              'email' => $request->email,
              'subject' => $request->subject,
              'message' => $request->message,
              'mail_subject' => 'vous avez reçu un email de contact',

            ];

            $admin = User::where('id',1)->first();

            Mail::to($admin->email)->send(new ContactEmail($mailData));

            session()->flash('success', 'Merci de nous avoir contact');
            return response()->json([
                'status' => true,
                'message' => 'Merci de nous avoir contact'
            ]);


        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
