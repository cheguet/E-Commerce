@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Coupon Code</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('coupons.index')}}" class="btn btn-success">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
      <form action="" method="post" id="discountForm" name="discountForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Code</label>
                                <input type="text" value="{{ $discountCoupon->code }}" name="code" id="code" class="form-control" placeholder="Code Coupon">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" value="{{ $discountCoupon->name }}" name="name" id="name" class="form-control" placeholder="Name Coupon">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Max Uses</label>
                                <input type="number" value="{{ $discountCoupon->max_uses }}" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Max Uses User</label>
                                <input type="number" value="{{ $discountCoupon->max_uses_user }}" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User">
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Type Coupon</label>
                                <select  name="type" id="type" class="form-control">
                                <option {{ ($discountCoupon->type == 'Percent' ) ? 'selected' : '' }} value="percent">Percent</option>
                                <option {{ ($discountCoupon->type == 'Fixed' ) ? 'selected' : '' }} value="fixed">Fixed</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Discount Amount</label>
                                <input type="text" value="{{ $discountCoupon->discount_amount }}" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">
                                <p></p>
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Min Amount</label>
                                <input type="text" value="{{ $discountCoupon->min_amount}}" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">
                            </div>
                        </div>		
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select  name="status" id="status" class="form-control">
                                <option {{ ($discountCoupon->status == 1 ) ? 'selected' : '' }} value="1">Active</option>
                                <option {{ ($discountCoupon->status == 0 ) ? 'selected' : '' }} value="0">Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Starts At</label>
                                <input type="text" value="{{ $discountCoupon->starts_at }}" name="starts_at" id="starts_at" class="form-control" placeholder="Starts At">
                                <p></p>
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Expires At</label>
                                <input type="text" value="{{ $discountCoupon->expires_at }}" name="expires_at" id="expires_at" class="form-control" placeholder="Expires At">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="slug">Description</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="3">
                                    {{ $discountCoupon->description }}
                                </textarea>
                            </div>
                        </div>										
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{route('coupons.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
     </form>
    </div>
    <!-- /.card -->
</section>  
<!-- /.content -->

@endsection


@section('customJs')

<script>

    $(document).ready(function(){
        $('#starts_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });

        $('#expires_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });
    });

    $("#discountForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{route("coupons.update",$discountCoupon->id)}}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){
            $("button[type=submit]").prop('disabled', false);


                if(response["status"] == true){
                    window.location.href="{{route('coupons.index')}}";
                    $("#code").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    $("#starts_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                }
                else
                {
                    var errors = response['errors'];
                    if(errors['code']){
                      $("#code").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['code']);
                    }else{
                      $("#code").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    }

                    if(errors['discount_amount']){
                      $("#discount_amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['discount_amount']);
                    }else{
                      $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    }

                    if(errors['starts_at']){
                      $("#starts_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['starts_at']);
                    }else{
                      $("#starts_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    }

                    if(errors['expires_at']){
                      $("#expires_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['expires_at']);
                    }else{
                      $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    }

                }

            }, error: function(jqXHR, exception){
            console.log("Something went wrong");
            }
        });
    });

</script>

@endsection