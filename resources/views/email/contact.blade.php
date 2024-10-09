<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Contact</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

   <h1>vous avez reçu un email de contact</h1>

    <p>Nom: {{ $mailData['name']}}</p>
    <p>Email: {{ $mailData['email']}}</p>
    <p>Sujet: {{ $mailData['subject']}}</p>
    <p>message:</p>
    <p>{{ $mailData['message']}}</p>
</body>
</html>