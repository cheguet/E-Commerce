@extends('front.layouts.app')

@section('content')
  
    <section class="container">
        <div class="col-md-12 text-center py-5">
            @if(Session::has('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{Session::get('success')}}
                    </div>
                </div>
            @endif
               <p> Identifiant de Votre Commande est : <strong> {{ $id }} </strong></p>
                <h1 class="h5">Pour finaliser votre commnade</h1>
                <a href="{{route('account.orderDetail',$id)}}">Cliquez ici</a>
    </section>

@endsection
@section('customJs')
@endsection