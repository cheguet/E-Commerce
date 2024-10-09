<script>

// debut register script
$("#registrationForm").submit(function(event){
       event.preventDefault();
        var formArray = $(this).serializeArray();
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{route("account.processRegister")}}',
            type: 'post',
            data: formArray,
            dataType: 'json',
            success: function(response){
              $("button[type=submit]").prop('disabled', false);

                var errors = response.errors;

                if(response.status == false){
                       
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

                    if(errors.password){
                        $("#password").siblings('p').addClass('invalid-feedback').html(errors.password);
                        $("#password").addClass('is-invalid');
                    }else{
                        $("#password").siblings('p').removeClass('invalid-feedback').html('');
                        $("#password").removeClass('is-invalid');
                    }
                    
                }else{
                    $("#name").siblings('p').removeClass('invalid-feedback').html('');
                    $("#name").removeClass('is-invalid');

                    $("#email").siblings('p').removeClass('invalid-feedback').html('');
                    $("#email").removeClass('is-invalid');

                    $("#password").siblings('p').removeClass('invalid-feedback').html('');
                    $("#password").removeClass('is-invalid');

                    window.location.href="{{route('account.login')}}";
                  
                }

            },
            error: function(JQXHR, exception){
            console.log("Something went wrong")
            }

        });
});

// fin register script





// debut paiement script 


$("#payment_method_one").click(function(){
     if($(this).is(":checked") == true){
        $("#btn-livraison").removeClass('d-none');
        $("#Payezmaintenant").addClass('d-none');
     }
});
 

$("#payment_method_two").click(function(){
    if($(this).is(":checked") == true){
    $("#btn-livraison").addClass('d-none');
    $("#Payezmaintenant").removeClass('d-none');
    }
});


$("#orderForm").submit(function(event){
    event.preventDefault();
    var formArray = $(this).serializeArray();
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
            url: '{{ route("front.processCheckout") }}',
            type: 'post',
            data: formArray,
            dataType: 'json',
            success: function(response){
                var errors = response.errors;
                $("button[type=submit]").prop('disabled', false);

                if(response.status == false){
                       
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
                           $("#email").siblings('p').addClass('invalid-feedback').html(errors.email);
                           $("#email").addClass('is-invalid');
                       }else{
                           $("#email").siblings('p').removeClass('invalid-feedback').html('');
                           $("#email").removeClass('is-invalid');
                       }

                       if(errors.country){
                           $("#country").siblings('p').addClass('invalid-feedback').html(errors.country);
                           $("#country").addClass('is-invalid');
                       }else{
                           $("#country").siblings('p').removeClass('invalid-feedback').html('');
                           $("#country").removeClass('is-invalid');
                       }

                       if(errors.address){
                           $("#address").siblings('p').addClass('invalid-feedback').html(errors.address);
                           $("#address").addClass('is-invalid');
                       }else{
                           $("#address").siblings('p').removeClass('invalid-feedback').html('');
                           $("#address").removeClass('is-invalid');
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

                       if(errors.zip){
                           $("#zip").siblings('p').addClass('invalid-feedback').html(errors.zip);
                           $("#zip").addClass('is-invalid');
                       }else{
                           $("#zip").siblings('p').removeClass('invalid-feedback').html('');
                           $("#zip").removeClass('is-invalid');
                       }

                       if(errors.mobile){
                           $("#mobile").siblings('p').addClass('invalid-feedback').html(errors.mobile);
                           $("#mobile").addClass('is-invalid');
                       }else{
                           $("#mobile").siblings('p').removeClass('invalid-feedback').html('');
                           $("#mobile").removeClass('is-invalid');
                       }
                       
                }else{
                   window.location.href="{{ url('/details/') }}/"+response.orderId;
                }
                
            }
    });
});

// fin paiement script


$("#apply-discount").click(function(){

$.ajax({
    url: '{{route("front.applyDiscount")}}',
    type: 'post',
    data: {code: $("#discount_code").val(), country_id: $("#country").val()},
    dataType: 'json',
    success: function(response){
        if(response.status == true){
            $("#shippingAmount").html('$ '+response.shippingCharge);
            $("#grandTotal").html('$ '+response.grandTotal);
            $("#discount_value").html('$ '+response.discount);
            $("#discount-response-wrapper").html(response.discountString);
            //window.location.href="{{route('front.checkout')}}";
        }else{
            $("#discount-response-wrapper").html("<span class='text-danger'>"+response.message+"<span>");
        }
      
    }, 
});

});

</script>