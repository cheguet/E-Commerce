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
                @include('front.account.common.message') 
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Changer le mot de Passe</h2>
                        </div>
                        <form action="" method="post" name="changePasswordForm" id="changePasswordForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">               
                                        <label for="name">Ancien mot de Passe</label>
                                        <input type="password" name="old_password" id="old_password" placeholder="Ancien mot de Ppasse" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">               
                                        <label for="name">Nouveau mot de Passe</label>
                                        <input type="password" name="new_password" id="new_password" placeholder="Nouveau mot de Passe" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">               
                                        <label for="name">Confirmez le mot de Passe</label>
                                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez le mot de Passe" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" id="submit" name="submit" class="btn btn-dark">Sauvegarder</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

@endsection
@section('customJs')

 

<script>
    $("#changePasswordForm").submit(function(event){
        event.preventDefault();
        var formArray = $(this).serializeArray();
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{route("account.newPasswordSave")}}',
            type: 'post',
            data: formArray,
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false); 
                var errors = response.errors;
                if(response.status == true)
                {
                  window.location.href= '{{ route("account.passwordChange") }}';
                }else{
                  
                    if(errors.old_password){
                        $("#old_password").siblings('p').addClass('invalid-feedback').html(errors.old_password);
                        $("#old_password").addClass('is-invalid');
                    }else{
                        $("#old_password").siblings('p').removeClass('invalid-feedback').html('');
                        $("#old_password").removeClass('is-invalid');
                    }

                    if(errors.new_password){
                        $("#new_password").siblings('p').addClass('invalid-feedback').html(errors.new_password);
                        $("#new_password").addClass('is-invalid');
                    }else{
                        $("#new_password").siblings('p').removeClass('invalid-feedback').html('');
                        $("#new_password").removeClass('is-invalid');
                    }

                    if(errors.confirm_password){
                        $("#confirm_password").siblings('p').addClass('invalid-feedback').html(errors.confirm_password);
                        $("#confirm_password").addClass('is-invalid');
                    }else{
                        $("#confirm_password").siblings('p').removeClass('invalid-feedback').html('');
                        $("#confirm_password").removeClass('is-invalid');
                    }

                }

            }
        });
    });
</script>
@endsection