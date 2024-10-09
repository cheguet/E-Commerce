<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinisialisé le mot de passe</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

   <p>{{$formData['user']->name}}</p>

   <h1>vous avez demandé un changement de mot de passe</h1>

   <p>veuillez cliquer sur le lien ci-dessous pour réinitialiser le mot de passe</p>
   
    <a href="{{ route('front.changePassword',$formData['token']) }}">Cliquer Ici!</a>

    <p>Merci.</p>
</body>
</html>