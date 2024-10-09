<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\CustomerAddresse;
use App\Models\Order;
use App\Models\Country;
use App\Models\Wishlist;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;





class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }

    public function register()
    {
      return view('front.account.register');
    }

    public function processRegister(Request $request)
    {
       $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
       ]);

        if($validator->passes()){
            
           $user = new User();
           $user->name = $request->name;
           $user->email = $request->email;
           $user->phone = $request->phone;
           $user->password = Hash::make($request->password);
           $user->save();
            
           session()->flash('success', 'vous avez été inscrit');
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


    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
       ]);

       if($validator->passes()){

          if(Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))){

           // return redirect()->route('account.profile');

            if(session()->has('url.intended')){

              return redirect(session()->get('url.intended'));

            }

           return redirect()->route('account.profile');
            
          }else{
            session()->flash('error', 'Email ou Mot de passe est incorrect');
            return redirect()->route('account.login');
          }

       }else{

        return redirect()->route('account.login')
        ->withErrors($validator)
        ->withInput($request->only('email'));

       }

    }

    
    public function profile()
    {
        $userId = Auth::user()->id;

        $countries = Country::orderBy('name','ASC')->get();

        $user = User::where('id',$userId)->first();

        $customerAddress = CustomerAddresse::where('user_id',$userId)->first();

        return view('front.account.profile',[
            'user'  => $user,
            'countries' => $countries,
            'customerAddress' => $customerAddress,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required'
        ]);
       
        if($validator->passes()){
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success', 'Mise a jour du profil');
            return response()->json([
                'status' => true,
                'message' => 'Mise a jour du profil'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required|min:10',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
       ]);
       
        if($validator->passes()){

            CustomerAddresse::updateOrCreate(
                ['user_id' =>  $userId],
                [
                   'user_id' =>  $userId,
                   'first_name' => $request->first_name,
                   'last_name' => $request->last_name,
                   'email' => $request->email,
                   'country_id' => $request->country_id,
                   'address' => $request->address,
                   'appartment' => $request->appartment,
                   'city' => $request->city,
                   'state' => $request->state,
                   'zip' => $request->zip,
                   'mobile' => $request->mobile
                ],
           );
            
            session()->flash('success', 'Mise a jour de l\'adresse de livraison');
            return response()->json([
                'status' => true,
                'message' => 'Mise a jour de l\'adresse de livraison'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')
        ->withErrors('success', 'Vous etes connecter');
    }

    public function orders()
    {
        $data = [];
        $user = Auth::user();

        $orders = Order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();

        $data['orders'] = $orders;

        return view('front.account.order',$data);
    }

    public function orderDetail($id)
    {
        $data = [];
        $user = Auth::user();

        $order = Order::where('user_id',$user->id)->where('id',$id)->first();
        $orderItems = OrderItem::where('order_id',$id)->get();

        $data['order'] = $order;
        $data['orderItems'] = $orderItems;


        return view('front.account.order-detail',$data);
    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        $data = [];
        $data ['wishlists'] = $wishlists;

        return view('front.account.wishlist',$data);
    }

    public function removeWishList(Request $request)   
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id',$request->id)->first();

        if($wishlist == null){
        
            session()->flash('error', 'Produit déjà supprimer');
            return response()->json([
                'status' => true,
            ]);
        }else{
            Wishlist::where('user_id', Auth::user()->id)->where('product_id',$request->id)->delete();
            session()->flash('success', 'Produit supprimer avec succès');
            return response()->json([
                'status' => true,
            ]);
        }
      
    }

    public function passwordChange()
    {
        return view('front.account.password-change');
    }

    public function newPasswordSave(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:3|same:confirm_password',
            'confirm_password' => 'required',
        ]);

           if ($validator->passes())
           {
                $user = User::select('id','password')->where('id', Auth::user()->id)->first();
                
                if(!Hash::check($request->old_password, $user->password)){ 
                    
                    session()->flash('error', 'Votre ancien mot de passe est incorrect s\'il te plaît, réessaye');
                    return response()->json([
                        'status' => true,
                    ]);
                }

                User::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password),
                ]);

                session()->flash('success', 'Mot de passe Modifier');
                return response()->json([
                    'status' => true,
                ]);
           }
           else
           {

                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);

           }
    }

    public function forgetPassword()
    {
        return view('front.account.forget-password');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails())
        {
          return redirect()->route('front.forgetPassword')->withInput()->withErrors($validator);
           
        }

        $token = Str::random(60);

        \DB::table('shoponline.password_resets')->where('email',$request->email)->delete();

        \DB::table('shoponline.password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $user = User::where('email',$request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
        ];

         // send email 
         Mail::to($request->email)->send(new ResetPasswordEmail($formData));

         return redirect()->route('front.forgetPassword')->with('success','s\'il vous plait verifier votre boite mail.');
    }

    public function changePassword($token)
    {

        $tokenExit =  \DB::table('shoponline.password_resets')->where('token',$token)->first();

        if($tokenExit == null){
            return redirect()->route('front.forgetPassword')->with('error','demande invalide'); 
        }

        return view('front.account.change-password',[
            'token' => $token,
        ]);
    }

    public function processnewpassword(Request $request)
    {
        $token = $request->token;
        $tokenExit =  \DB::table('shoponline.password_resets')->where('token',$token)->first();

        if($tokenExit == null){
            return redirect()->route('front.forgetPassword')->with('error','demande invalide'); 
        }

        $user = User::where('email',$tokenExit->email)->first();

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:3',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->fails())
        {
            
          return redirect()->route('front.changePassword',$token)->withErrors($validator);
        }

        User::where('id',$user->id)->update([
            'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('account.login')->with('success', 'Mot de passe Changer');
            
    }

}
