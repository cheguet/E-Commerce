@extends('front.layouts.app')

@section('content')
  
    <section class="container">
        <div class="col-md-12 text-center py-5">
               <h1 class="h5">Merci pour la commande, vous venez de finaliser votre paiement.</h1> 
                <p>un agent vous contactera dans les plus brefs délais pour la livraison.</p>
                       <a href="{{route('front.shop')}}"> -> Des Nouvelles choses</a>
    </section>

@endsection
@section('customJs')
@endsection