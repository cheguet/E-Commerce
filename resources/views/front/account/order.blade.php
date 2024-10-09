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
                            <h2 class="h5 mb-0 pt-2 pb-2">Mes commandes</h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table  commandetable">
                                    <thead> 
                                        <tr>
                                            <th>Identifiant #</th>
                                            <th>Date d'achat</th>
                                            <th>Statut</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($orders->isNotEmpty())
                                        @foreach ($orders as $order )
                                        <tr>
                                            <td data-title="ID">
                                             <a href="{{route('account.orderDetail',$order->id)}}">CMD {{$order->id}}</a>
                                            </td>
                                            <td data-title="Date d'achat">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y')}}</td>
                                            <td data-title="Statut">
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
                                            </td>
                                            <td data-title="Total">$ {{number_format($order->grand_total,2)}}</td>
                                        </tr>   
                                        @endforeach
                                        @else 
                                        <tr>
                                             <td colspan="3">Oups pas de commande !!</td>
                                        </tr>
                                        @endif                                    
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
<style>
@media (max-width: 30em){
        body{
            .commandetable{
                width: 100%;
            }
            .commandetable tr{
                display: flex;
                flex-direction: column;
                border: 1px solid grey;
                padding: 1em;
                margin-bottom: 1em;
            }
            .commandetable td[data-title]{
                display: flex;
            }
            .commandetable td,
            .produittable th{
                border: none;
            }
            .commandetable td[data-title]::before{
                content:attr(data-title);
                width: 80px;
                color: silver;
                font-weight: bold;
            }
            .commandetable thead{
                display: none;
            }
        }	
    }
</style>
@endsection