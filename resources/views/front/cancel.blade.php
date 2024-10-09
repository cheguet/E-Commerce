@extends('front.layouts.app')

@section('content')
  
    <section class="container">
        <div class="col-md-12 text-center py-5">
               <h1 class="h5">le processus a été annuler</h1> 
                <a href="{{route('account.orders')}}">  -> Mes Commandes</a>
    </section>

@endsection
@section('customJs')
@endsection