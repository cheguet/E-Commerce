<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\Brand;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;






class HomeController extends Controller
{
    public function index()
    {

      $totalOrders = Order::where('status','!=','cancelled')->count();
      $totalProducts = Product::where('status','!=','cancelled')->count();
      $totalCategories = Category::where('status', 1)->count();
      $totalBrands = Brand::where('status', 1)->count();
      $totalCustomers = User::where('role', 1)->count();

      $totalRevenue = Order::where('status','!=','cancelled')->sum('grand_total');

      // this month revenue

      $starOfMonth = Carbon::now()->startofMonth()->format('Y-m-d');
      $currentDate = Carbon::now()->format('Y-m-d');

      $revenueThisMonth = Order::where('status','!=','cancelled')
                            ->whereDate('created_at','>=', $starOfMonth)
                            ->whereDate('created_at','<=', $currentDate)
                            ->sum('grand_total');

      $today = Carbon::today();
      $revenueOfTheDay = Order::where('status','!=','cancelled')
                              ->whereDate('created_at','=', $today)
                              ->sum('grand_total');

          
         //dd($revenueOfTheDay);
        // last Month revenu
      $lastMonthSartDate = Carbon::now()->subMonth()->startofMonth()->format('Y-m-d');
      $lastMonthEndDate = Carbon::now()->subMonth()->endofMonth()->format('Y-m-d');
      $lastMonthName = Carbon::now()->subMonth()->endofMonth()->format('M');


      $revenueLastMonth = Order::where('status','!=','cancelled')
                            ->whereDate('created_at','>=', $lastMonthSartDate)
                            ->whereDate('created_at','<=', $lastMonthEndDate)
                            ->sum('grand_total');



        // Last 30 days sale

      $lastThirtyDayStartDate  = Carbon::now()->subDays(30)->format('Y-m-d');

      $revenueLastThirtyDays = Order::where('status','!=','cancelled')
                            ->whereDate('created_at','>=', $lastThirtyDayStartDate)
                            ->whereDate('created_at','<=', $currentDate)
                            ->sum('grand_total');


         // delete temp image 
      $daybeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
      $tempImages = TempImage::where('created_at','<=',$daybeforeToday)->get();

      foreach($tempImages as $tempImage) {
            $path = public_path('/temp/'.$tempImage->name);
            $thumbpath = public_path('/temp/thumb/'.$tempImage->name);
            
            // delete main image
            if(File::exists($path)){
              File::delete($path);
            }
  
            // delete thumb image
            if(File::exists($thumbpath)){
              File::delete($thumbpath);
            }

            TempImage::where('id',$tempImage->id)->delete();
      }

      return view('admin.dashboard',[
        'totalOrders' => $totalOrders,
        'totalProducts' => $totalProducts,
        'totalCustomers' => $totalCustomers,
        'totalRevenue'  => $totalRevenue,
        'revenueThisMonth' => $revenueThisMonth,
        'revenueLastMonth' => $revenueLastMonth,
        'revenueLastThirtyDays' => $revenueLastThirtyDays,
        'totalCategories'  => $totalCategories,
        'totalBrands'  => $totalBrands,
        'lastMonthName' => $lastMonthName,
        'revenueOfTheDay' => $revenueOfTheDay,
      ]);    
    }

    public function logout() 
    {
         Auth::guard('admin')->logout();
         return redirect()->route('admin.login');      
    }

}
