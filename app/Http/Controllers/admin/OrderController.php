<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders = $orders->leftJoin('users','users.id','orders.user_id');
         
        if(!empty($request->get('keyword'))){
          $orders = $orders->where('users.name','like','%'.$request->get('keyword').'%');
          $orders = $orders->orwhere('users.email','like','%'.$request->get('keyword').'%');
          $orders = $orders->orwhere('orders.id','like','%'.$request->get('keyword').'%');
        }
        $orders = $orders->paginate(10);

        return view('admin.orders.list',[
            'orders'  => $orders,
        ]);
    }

    public function detail($orderId)
    {
         $order = Order::select('orders.*','countries.name as countryName')
         ->where('orders.id',$orderId)
         ->leftJoin('countries','countries.id','orders.country_id')
         ->first();

         $orderItems = OrderItem::where('order_id',$orderId)->get();

        return view('admin.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }

    public function changeOrderStatus(Request $request, $orderI)
    {
        $order = Order::find($orderI);
        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order Status update successfully';
        session()->flash('success',$message);
        return  response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId)
    {
        orderEmail($orderId, $request->userType);

        $message = 'Order email send successfully';
        session()->flash('success',$message);
        return  response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
