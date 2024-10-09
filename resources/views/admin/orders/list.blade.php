@extends('admin.layouts.app')

@section('content')

    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order</h1>
                </div>
                <div class="col-sm-6 text-right">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{route("orders.index")}}'"  class="btn btn-success btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{Request::get('keyword')}}" type="text"  name="keyword" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                                </div>
                            </div>
                        </div>	
                    </div>
                </form>
                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Orders #</th>											
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isNotEmpty())
                            @foreach ($orders as $order)
                            <tr>
                                <td><a href="{{route('orders.detail',$order->id) }}">#CMD-{{$order->id}}</a></td>
                                <td>{{$order->first_name}} {{$order->last_name}}</td>
                                <td>{{$order->email}}</td>
                                <td>{{$order->mobile}}</td>
                                <td>
                                    @if ($order->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif ($order->status == 'shipped')
                                    <span class="badge bg-info">Shipped</span>
                                    @elseif($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                    @else
                                    <span class="badge bg-danger">Cancellad</span>
                                    @endif 
                                    @if ($order->payment_status == 'not paid')
                                    / <span class="badge bg-info"> Not Paid</span> 
                                    @else
                                    / <span class="badge bg-success"> Paid</span> 
                                    @endif 
                                </td>
                                <td>{{number_format($order->grand_total,2)}}</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y')}}</td>																				
                            </tr>
                            @endforeach
                            @else
                            <tr>
                            <td colspan="5">Records Not Found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>										
                </div>
                <div class="card-footer clearfix">
                         {{$orders->links()}} 
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection
@section('customJs')
@endsection