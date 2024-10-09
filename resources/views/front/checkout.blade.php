@extends('front.layouts.app')

@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Accueil</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Boutique</a></li>
                    <li class="breadcrumb-item">Vérification</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
               <!-- @if (Session::has('error'))
                  <div class="col-md-12">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert" >
                         {{Session::get('error')}}
                  </div>
                </div>
                @endif -->
            <form action="" method="post" id="orderForm" name="orderForm">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Adresse de livraison</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Prénom" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>            
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Nom de famille" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control" >
                                                <option value="" disabled selected>Choisissez un pays</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                    <option {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : '' }} value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Adresse" class="form-control">
                                            {{ (!empty($customerAddress)) ? $customerAddress->address : '' }}
                                            </textarea>
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="appartment" id="appartment" class="form-control" placeholder="Appartement,(facultatif)" value="{{ (!empty($customerAddress)) ? $customerAddress->appartment : '' }}">
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="Ville" value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control" placeholder="État" value="{{ (!empty($customerAddress)) ? $customerAddress->state : '' }}">
                                            <p></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control" placeholder="code postal" value="{{ (!empty($customerAddress)) ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Téléphone" value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>            
                                    </div>
                                    

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Notes de commande (facultatif)" class="form-control">
                                            {{ (!empty($customerAddress)) ? $customerAddress->notes : '' }}
                                            </textarea>
                                        </div>            
                                    </div>

                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="sub-title">
                            <h2>Détails de la commande</h3>
                        </div>                    
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item) 
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{$item->name}} (X) {{$item->qty}}</div>
                                    <div class="h6">$ {{$item->price*$item->qty}}</div>
                                </div>
                                @endforeach
                                
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>total</strong></div>
                                    <div class="h6"><strong>$ {{Cart::subtotal()}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Discount</strong></div>
                                    <div class="h6"><strong id="discount_value">$ {{ $discount }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2"> 
                                    <div class="h6"><strong>Expédition</strong></div>
                                    <div class="h6"><strong id="shippingAmount">$ {{ number_format($totalShippingCharge,2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal">$ {{ number_format($grandTotal,2)}}</strong></div>
                                </div>                            
                            </div>
                        </div>
                        
                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Appliquer Coupon</button>
                        </div>

                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                            <div class="mt-4" id="discount-response">
                                <strong>{{ Session::get('code')->code }}</strong>
                                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                            </div>
                            @endif
                        </div>
                        
                        <div class="card payment-form">
                          <h3 class="card-title h5 mb-3"></h3>

                            <div class="form-check">
                                <input checked  type="radio" name="payment_method" value="cod" id="payment_method_one">
                                <label for="payment_method_one" class="form-check-label">Adresse de livraison</label>
                            </div>
                            
                            <!-- <div class="form-check">
                                <input  type="radio" name="payment_method" value="cod" id="payment_method_two">
                                <label for="payment_method_two" class="form-check-label">Payez maintenant</label>
                            </div> -->
               
                        </div>
                        <div class="pt-4" id="btn-livraison">
                            <button type="submit" class="btn-dark btn btn-block w-100">Entregistrer la commande</button>
                        </div>  
                        <!-- CREDIT CARD FORM ENDS HERE -->
                    </div>
                </div>
            </form>
            <!-- <div class="row maintenant" >
                <div class="col-md-12">
                    <div class="card mt-3" id="Payezmaintenant">
                        <div class="card-body" id="coco">
                            <form action="/session" method="POST">
                                @csrf
                                <div class="pt-4" id="btn-strip">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button type="submit" class="btn-dark btn btn-block w-100">Payez maintenant</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>

@endsection
@section('customJs')

<script>

    $("#country").change(function(){
        
        $.ajax({
            url: '{{route("front.getOrderSummery")}}',
            type: 'post',
            data: {country_id: $(this).val()},
            dataType: 'json',
            success: function(response){
            if(response.status == true){
                $("#shippingAmount").html('$ '+response.shippingCharge);
                $("#grandTotal").html('$ '+response.grandTotal);
            }
            }, 
        });

    });

    $('body').on('click',"#remove-discount",function(){
        $.ajax({
            url: '{{route("front.removeCoupon")}}',
            type: 'post',
            data: {country_id: $("#country").val()},
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#shippingAmount").html('$ '+response.shippingCharge);
                    $("#grandTotal").html('$ '+response.grandTotal);
                    $("#discount_value").html('$ '+response.discount);
                    $("#discount-response").html('');
                    $("#discount_code").val('');
                    //window.location.href="{{route('front.checkout')}}";
                }
            
            }, 
        });

    });

@endsection