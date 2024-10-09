@extends('front.layouts.app')

@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">Mon compte</a></li>
                    <li class="breadcrumb-item">Paramètre</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Detail de Commande</h2>
                        </div>

                        <div class="card-body pb-0">
                            <!-- Info -->
                            <div class="card card-sm">
                                <div class="card-body bg-light mb-3">
                                    <div class="row">
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">N ° de commande:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                            {{ $order->id }}
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Date Statut:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                <time datetime="2019-10-01">
                                                   @if (!empty($order->shipped_date))
                                                   {{ \Carbon\Carbon::parse($order->shipped_date)->format('d/m/Y')}}
                                                   @else
                                                       n/a
                                                   @endif
                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Statut:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                                @if ($order->status == 'pending')
                                                <span class="badge bg-warning">En attente</span>
                                                @elseif ($order->status == 'shipped')
                                                <span class="badge bg-info">Expédié</span>
                                                @elseif($order->status == 'delivered')
                                                <span class="badge bg-success">Livré</span>
                                                @else
                                                <span class="badge bg-danger">Annulé</span>
                                                @endif
                                                @if ($order->payment_status == 'not paid')
                                                /<span class="badge bg-info">Non Payer</span> 
                                                @else
                                                /<span class="badge bg-success">Payer</span> 
                                                @endif  
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Montant:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                            $ {{number_format($order->grand_total,2)}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer p-3">

                            <!-- Heading -->
                            <h6 class="mb-7 h5 mt-4">Articles de commande : ({{$orderItems->count()}})</h6>

                            <!-- Divider -->
                            <hr class="my-3">

                            <!-- List group -->
                            <ul>
                                @if ($orderItems->isNotEmpty())
                                    @foreach ($orderItems as $orderItem)
                                        @php
                                        $productImage = getProductImage($orderItem->product_id);
                                        @endphp
                                        <li class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-4 col-md-3 col-xl-2">
                                                     <!-- Image -->
                                                    @if (!empty($productImage->image))
                                                    <img class="img-fluid" src="{{asset('uploads/product/small/'.$productImage->image)}}" class="img-thumbnail" width="50" >
                                                    @else
                                                    <img class="img-fluid" src="{{asset('admin-assets/img/default-150x150.png')}}" class="img-thumbnail" width="50" >
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <!-- Title -->
                                                    <p class="mb-4 fs-sm fw-bold">
                                                        {{ $orderItem->name}} (x) {{ $orderItem->qty}} <br>
                                                        <span class="text-muted">${{ number_format($orderItem->total,2)}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>                      
                    </div>
                    
                    <div class="card card-lg mb-5 mt-3">
                        <div class="card-body">
                            <!-- Heading -->
                            <h6 class="mt-0 mb-3 h5">Total de la commande</h6>

                            <!-- List group -->
                            <ul>
                                <li class="list-group-item d-flex">
                                    <span>total</span>
                                    <span class="ms-auto">$ {{number_format($order->subtotal,2)}}</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Rabais {{(!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : '' }}</span>
                                    <span class="ms-auto">$ {{number_format($order->discount,2)}}</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Expédition</span>
                                    <span class="ms-auto">$ {{number_format($order->shipping,2)}}</span>
                                </li>
                                <li class="list-group-item d-flex fs-lg fw-bold">
                                    <span>Total</span>
                                    <span class="ms-auto">$ {{number_format($order->grand_total,2)}}</span>
                                </li>
                            </ul>
                        </div>
                        @if ($order->payment_status == 'not paid')
                        <form action="/session" method="POST">
                            @csrf
                            <div class="pt-4" id="btn-strip">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="total" value="{{number_format($order->grand_total,2)}}"> 
                                <input type="hidden" name="numerocommande" value="{{$order->id}}">
                                <button type="submit" class="btn-dark btn btn-block w-100">Payez maintenant</button>
                            </div>
                        </form>
                        @else

                        @endif  
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@section('customJs')
@endsection