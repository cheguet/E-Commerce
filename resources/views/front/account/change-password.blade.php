@extends('front.layouts.app')

@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Accueil</a></li>
                    <li class="breadcrumb-item">Mot de passe oublié</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
                @if(Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{Session::get('success')}}
                        </div>
                    </div>
                @endif 
                @if(Session::has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{Session::get('error')}}
                        </div>
                    </div>
                @endif  
            <div class="login-form">       
                <form action="{{route('front.processnewpassword')}}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token}}">
                    <h4 class="modal-title">Nouveau mot de passe</h4>
                    <div class="form-group">
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Mot de passe" name="new_password">
                        @error('new_password')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirmer le mot de passe " name="confirm_password">
                        @error('confirm_password')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Changer">              
                </form>			
                <div class="text-center small"><a href="{{route('account.login')}}">Se connecter</a></div>
            </div>
        </div>
    </section>

@endsection
@section('customJs')
@endsection