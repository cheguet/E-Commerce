@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="row">

           <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalProducts}}</h3>
                        <p>Products</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('products.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalCategories}}</h3>
                        <p>Categories</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('categories.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalBrands}}</h3>
                        <p>Brands</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('brands.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalCustomers}}</h3>
                        <p>Customers</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('users.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalOrders}}</h3>
                        <p>orders Total</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('orders.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$revenueOfTheDay}}</h3>
                        <p>Sale today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('orders.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>$ {{ number_format($totalRevenue,2)}}</h3>
                        <p>Sale Total</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>$ {{ number_format($revenueThisMonth,2)}}</h3>
                        <p>Revenue this Month</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>$ {{ number_format($revenueLastMonth,2)}}</h3>
                        <p>Revenue last Month ({{$lastMonthName}})</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>$ {{ number_format($revenueLastThirtyDays,2)}}</h3>
                        <p>Revenue last 30 days</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>

        </div>
    </div>					
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
</script>
@endsection