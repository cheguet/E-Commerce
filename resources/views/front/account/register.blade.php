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

    <section class=" section-10">
        <div class="container">
            <div class="login-form">    
                <form action="" method="post" name="registrationForm" id="registrationForm">
                    <h4 class="modal-title">S'inscrire Maintenant</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nom" id="name" name="name">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Téléphone" id="phone" name="phone">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Mot de passe" id="password" name="password">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirmer le Mot de passe" id="password_confirmation" name="password_confirmation">
                        <p></p>
                    </div>
                    <div class="form-group small">
                        <a href="#" class="forgot-link">Mot de passe oublié?</a>
                    </div> 
                    <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">S'inscrire</button>
                </form>			
                <div class="text-center small">Vous avez déjà un compte? <a href="{{route('account.login')}}">Connecte-toi maintenant</a></div>
            </div>
        </div>
    </section>



@endsection
@section('customJs')

@endsection