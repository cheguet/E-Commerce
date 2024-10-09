@extends('front.layouts.app')

@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
             @include('front.account.common.message')
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Accueil</a></li>
                    <li class="breadcrumb-item">{{$page->name}}</li>
                </ol>
            </div>
        </div>
    </section>
    @if ($page->slug == 'contacts')
        <section class=" section-10">
            <div class="container">
                <div class="section-title mt-5 ">
                    <h2>J'adore avoir de vos nouvelles</h2>
                </div>   
            </div>
        </section>
        <section>
            <div class="container">          
                <div class="row">
                    <div class="col-md-6 mt-3 pe-lg-5">
                        {!! $page->content !!}
                        <address>
                        Salem Camara <br>
                        711-2880 Nulla St.<br> 
                        14 Rue sakifat hay lala Aicha 96522<br>
                        <a href="tel:+xxxxxxxx">(XXX) 555-2368</a><br>
                        <a href="mailto:jim@rock.com">salem@cam.com</a>
                        </address>                    
                    </div>

                    <div class="col-md-6">
                        <form class="shake" role="form" method="post" id="contactForm" name="contactForm">
                            <div class="mb-3">
                                <label class="mb-2" for="name">Nom</label>
                                <input class="form-control" id="name" type="text" name="name" required data-error="Please enter your name">
                                <p class="help-block with-errors"></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="mb-2" for="email">Email</label>
                                <input class="form-control" id="email" type="email" name="email" required data-error="Please enter your Email">
                                <p class="help-block with-errors"></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="mb-2">Sujet</label>
                                <input class="form-control" id="subject" type="text" name="subject" required data-error="Please enter your message subject">
                                <p class="help-block with-errors"></p>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="mb-2">Message</label>
                                <textarea class="form-control" rows="3" id="message" name="message" required data-error="Write your message"></textarea>
                                <p class="help-block with-errors"></p>
                            </div>
                        
                            <div class="form-submit">
                                <button class="btn btn-dark" type="submit" id="form-submit"><i class="material-icons mdi mdi-message-outline"></i> Envoyer</button>
                                <div id="msgSubmit" class="h3 text-center hidden"></div>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class=" section-10">
            <div class="container">
                <h1 class="my-3">{{$page->name}}</h1>
                {!! $page->content !!}
            </div>
        </section>
    @endif
    



@endsection
@section('customJs')

<script>

$("#contactForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{route("front.sendContactEmail")}}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                $("button[type=submit]").prop('disabled', false);


                    if(response["status"] == true){

                         window.location.href="{{route('front.page',$page->slug)}}";

                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                        $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                        $("#subject").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                        $("#message").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                    }
                    else
                    {

                        var errors = response['errors'];

                        if(errors['name']){
                        $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors['name']);
                        }else{
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                        }

                        if(errors['subject']){
                        $("#subject").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors['subject']);
                        }else{
                        $("#subject").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                        }

                        if(errors['message']){
                        $("#message").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors['message']);
                        }else{
                        $("#message").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                        }
                   }

               }, error: function(jqXHR, exception){
               console.log("Something went wrong");
               }
          });
       });
</script>

@endsection