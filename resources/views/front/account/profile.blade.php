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
<!--                             <button type="button" onclick="window.location.href='{{route("front.checkout")}}'"  class="btn btn-success btn-sm float-end">Retour à la Caisse</button>
 -->                    <h2 class="h5 mb-0 pt-2 pb-2">Informations personnelles</h2>
                        </div>
                        <form action="" method="post" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">               
                                        <label for="name">Nom</label>
                                        <input value="{{ $user->name}}" type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">            
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email}}"  type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="phone">Phone</label>
                                        <input value="{{ $user->phone}}"  type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">Mise à jour</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                       <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Adresse de Livraison</h2>
                        </div>
                        <form action="" method="post" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">               
                                        <label for="name">Prenom</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}" type="text" name="first_name" id="first_name" placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">               
                                        <label for="name">Nom</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">            
                                        <label for="email">Email</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->email : '' }}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">                                    
                                        <label for="phone">Mobile</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}"  type="text" name="mobile" id="mobile" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="phone">Pays</label>
                                         <select name="country_id" id="country_id" class="form-control" >
                                            <option disabled selected value="">Sélectionner un Pays</option>
                                            @if ($countries->isNotEmpty())
                                                @foreach ($countries as $country)
                                                <option {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : '' }} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            @endif
                                         </select>
                                        <p></p>
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="phone">Adresse</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="3">
                                           {{ (!empty($customerAddress)) ? $customerAddress->address : '' }}
                                        </textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">                                    
                                        <label for="phone">Appartement</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->appartment : '' }}"  type="text" name="appartment" id="appartment" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">                                    
                                        <label for="phone">Ville</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}"  type="text" name="city" id="city" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">                                    
                                        <label for="phone">État</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->state : '' }}"  type="text" name="state" id="state" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">                                    
                                        <label for="phone">Code Postal</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->zip : '' }}"  type="text" name="zip" id="zip" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">Mise à jour</button>
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
    $("#profileForm").submit(function(event){
        event.preventDefault();
        var formArray = $(this).serializeArray();
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{route("account.updateProfile")}}',
            type: 'post',
            data: formArray,
            dataType: 'json',
            success: function(response){

                var errors = response.errors;
                if(response.status == true)
                {
                  window.location.href= '{{ route("account.profile") }}';
                }else{
                  
                    if(errors.name){
                        $("#name").siblings('p').addClass('invalid-feedback').html(errors.name);
                        $("#name").addClass('is-invalid');
                    }else{
                        $("#name").siblings('p').removeClass('invalid-feedback').html('');
                        $("#name").removeClass('is-invalid');
                    }

                    if(errors.email){
                        $("#email").siblings('p').addClass('invalid-feedback').html(errors.email);
                        $("#email").addClass('is-invalid');
                    }else{
                        $("#email").siblings('p').removeClass('invalid-feedback').html('');
                        $("#email").removeClass('is-invalid');
                    }

                    if(errors.phone){
                        $("#phone").siblings('p').addClass('invalid-feedback').html(errors.phone);
                        $("#phone").addClass('is-invalid');
                    }else{
                        $("#phone").siblings('p').removeClass('invalid-feedback').html('');
                        $("#phone").removeClass('is-invalid');
                    }

                }

            }
        });
    });



    $("#addressForm").submit(function(event){
        event.preventDefault();
        var formArray = $(this).serializeArray();
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{route("account.updateAddress")}}',
            type: 'post',
            data: formArray,
            dataType: 'json',
            success: function(response){

                var errors = response.errors;
                if(response.status == true)
                {
                  window.location.href= '{{ route("account.profile") }}';
                }else{
                  
                    if(errors.first_name){
                        $("#first_name").siblings('p').addClass('invalid-feedback').html(errors.first_name);
                        $("#first_name").addClass('is-invalid');
                    }else{
                        $("#first_name").siblings('p').removeClass('invalid-feedback').html('');
                        $("#first_name").removeClass('is-invalid');
                    }

                    if(errors.last_name){
                        $("#last_name").siblings('p').addClass('invalid-feedback').html(errors.last_name);
                        $("#last_name").addClass('is-invalid');
                    }else{
                        $("#last_name").siblings('p').removeClass('invalid-feedback').html('');
                        $("#last_name").removeClass('is-invalid');
                    }
                    
                    if(errors.email){
                        $("#addressForm #email").siblings('p').addClass('invalid-feedback').html(errors.email);
                        $("#addressForm #email").addClass('is-invalid');
                    }else{
                        $("#addressForm #email").siblings('p').removeClass('invalid-feedback').html('');
                        $("#addressForm #email").removeClass('is-invalid');
                    }

                    if(errors.mobile){
                        $("#mobile").siblings('p').addClass('invalid-feedback').html(errors.mobile);
                        $("#mobile").addClass('is-invalid');
                    }else{
                        $("#mobile").siblings('p').removeClass('invalid-feedback').html('');
                        $("#mobile").removeClass('is-invalid');
                    }

                    if(errors.country_id){
                        $("#country_id").siblings('p').addClass('invalid-feedback').html(errors.country_id);
                        $("#country_id").addClass('is-invalid');
                    }else{
                        $("#country_id").siblings('p').removeClass('invalid-feedback').html('');
                        $("#country_id").removeClass('is-invalid');
                    }

                    if(errors.address){
                        $("#address").siblings('p').addClass('invalid-feedback').html(errors.address);
                        $("#address").addClass('is-invalid');
                    }else{
                        $("#address").siblings('p').removeClass('invalid-feedback').html('');
                        $("#address").removeClass('is-invalid');
                    }

                    if(errors.appartment){
                        $("#appartment").siblings('p').addClass('invalid-feedback').html(errors.appartment);
                        $("#appartment").addClass('is-invalid');
                    }else{
                        $("#appartment").siblings('p').removeClass('invalid-feedback').html('');
                        $("#appartment").removeClass('is-invalid');
                    }

                    if(errors.city){
                        $("#city").siblings('p').addClass('invalid-feedback').html(errors.city);
                        $("#city").addClass('is-invalid');
                    }else{
                        $("#city").siblings('p').removeClass('invalid-feedback').html('');
                        $("#city").removeClass('is-invalid');
                    }

                    if(errors.state){
                        $("#state").siblings('p').addClass('invalid-feedback').html(errors.state);
                        $("#state").addClass('is-invalid');
                    }else{
                        $("#state").siblings('p').removeClass('invalid-feedback').html('');
                        $("#state").removeClass('is-invalid');
                    }

                    if(errors.state){
                        $("#zip").siblings('p').addClass('invalid-feedback').html(errors.zip);
                        $("#zip").addClass('is-invalid');
                    }else{
                        $("#zip").siblings('p').removeClass('invalid-feedback').html('');
                        $("#zip").removeClass('is-invalid');
                    }

                }

            }
        });
    });
</script>
@endsection