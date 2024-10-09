<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mail Commande</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

          @if ($mailData['userType'] == 'customer')
            <h1>Merci pour votre commande</h1>
            <h2>Votre numéro de commande est: #CMD-{{ $mailData['order']->id }}</h2>
            <h2>adresse de livraison</h2>
          @else
            <h1>Vous avez reçu sur commande</h1>
            <h2>numéro de commande est: #CMD-{{ $mailData['order']->id }}</h2>
          @endif
          
            <address>
                <strong>{{$mailData['order']->first_name}}  {{$mailData['order']->last_name}}</strong><br>
                {{$mailData['order']->address}}<br>
                {{$mailData['order']->city}} , {{$mailData['order']->zip}} {{ getCountryInfo($mailData['order']->country_id)->name}}<br>
                Phone: {{$mailData['order']->mobile}}<br>
                Email: {{$mailData['order']->email}}
            </address>

    <h2>Articles</h2>
    <table cellpadding="3" cellpadding="3" style="border:0px;;" width="700">
        <thead>
            <tr style="background:#CCC;">
                <th>Article</th>
                <th>Prix</th>
                <th>Qty</th>                                        
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>$ {{$item->price}}</td>                                        
                <td>{{$item->qty}}</td>
                <td>$ {{$item->total}}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="3" style="text-align:right;">Sous total:</th>
                <td>$ {{number_format($mailData['order']->subtotal,2)}}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align:right;">Rabais: {{(!empty($mailData['order']->coupon_code)) ? '('.$mailData['order']->coupon_code.')' : '' }}</th>
                <td>$ {{number_format($mailData['order']->discount,2)}}</td>
            </tr>
            
            <tr>
                <th colspan="3" style="text-align:right;">Expédition:</th>
                <td>$ {{number_format($mailData['order']->shipping,2)}}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align:right;">Grand Total:</th>
                <td>$ {{number_format($mailData['order']->grand_total,2)}}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>