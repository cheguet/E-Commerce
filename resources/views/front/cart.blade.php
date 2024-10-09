@extends('front.layouts.app')

@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home')}}">Accueil</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop')}}">Boutique</a></li>
                    <li class="breadcrumb-item">Panier</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            <div class="row">
                @if(Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{Session::get('success')}}
                        </div>
                    </div>
                @endif
               

                @if (Session::has('error'))
                <div class="col-md-12">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert" >
                         {{Session::get('error')}}
                  </div>
                </div>
                @endif
                @if(Cart::count() > 0)
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table carttable" id="cart">
                            <thead>
                                <tr>
                                    <th>Articles</th>
                                    <th>Prix</th>
                                    <th>Quantités</th>
                                    <th>Total</th>
                                    <th>Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartContent as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if (!empty($item->options->productImage->image))
                                            <img  src="{{asset('uploads/product/small/'.
                                                $item->options->productImage->image)}}">
                                            @else
                                            <img  src="{{asset('admin-assets/img/default-150x150.png')}}" >
                                            @endif

                                            <h2>{{$item->name}}</h2>
                                        </div>
                                    </td>
                                    <td data-title="Prix">$ {{$item->price}}</td>
                                    <td data-title="Quantité">
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-append decrement-btn" style="cursor:pointer">
                                            <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{$item->rowId}}">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm  border-0 text-center" value="{{$item->qty}}">
                                        <div class="input-group-append increment-btn" style="cursor:pointer">
                                            <button  class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{$item->rowId}}">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    </td>
                                    <td data-title="Total">
                                        $ {{$item->price*$item->qty}}
                                    </td>
                                    <td data-title="Supprimer">
                                        <button class="btn btn-sm btn-danger" onclick="deleteItem('{{$item->rowId}}')"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr> 
                                @endforeach                          
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">            
                    <div class="card cart-summery">
                        <div class="card-body">
                            <div class="sub-title">
                                <h2 class="bg-white">RÉSUMÉ DU PANIER</h3>
                            </div> 
                            <div class="d-flex justify-content-between pb-2">
                                <div>Total</div>
                                <div>$ {{ Cart::subtotal() }}</div>
                            </div>
                            <div class="pt-5">
                                <a href="{{route('front.checkout') }}" class="btn-dark btn btn-block w-100">Suivant</a>
                            </div>
                        </div>
                    </div>     
                </div>
                 @else
                  <div class="col-md-12">
                        <div class="cart">
                            <div class="cart-body">
                                <h1 class="h5 text-center" >Oups Votre panier est vide</h1>
                            </div>
                        </div>
                  </div>
                @endif 
            </div>
        </div>
    </section>

@endsection
@section('customJs')
<style>
@media (max-width: 30em){
        body{
            .carttable{
                width: 100%;
            }
            .carttable tr{
                display: flex;
                flex-direction: column;
                border: 1px solid grey;
                padding: 1em;
                margin-bottom: 1em;
            }
            .carttable td[data-title]{
                display: flex;
            }
            .carttable td,
            .produittable th{
                border: none;
            }
            .carttable td[data-title]::before{
                content:attr(data-title);
                width: 100px;
                color: silver;
                font-weight: bold;
            }
            .carttable thead{
                display: none;
            }
        }	
    }
</style>
@endsection