@extends('layouts.app')
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<title>My Account</title>
@section('content')
<div class="msg">
    @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show msg" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                <i class="msg-c">{{session('success')}}</i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show msg" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{session('danger')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
</div>

<main id="main" style="width: 100%; margin: auto; margin-top: 30px;">

    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center" style="margin-top: 10px;">
          <h2 class="d-flex">My account</h2>
          <span>Current balance: <strong> {{Auth::user()->account}}</strong></span>
          <ol>
            <li><a href="/">Home</a></li>
            <li>My Account</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->


    <section id="portfolio" class="portfolio">
        <div class="container">

          <div class="" data-aos="fade-up" style="margin-top: 0;">
            <table class="table table-borderless datatable table-hover table-sm all-tables">
                <thead>
                  <tr style="height: 35px; background-color: #fafafa;">
                    <th style="width: 4%" scope="col">#</th>
                    <th style="width: 15%" scope="col">Username</th>
                    <th style="width: 15%" scope="col">Amount</th>
                    <th style="width: 15%" scope="col">Date</th>

                  </tr>
                </thead>
                <tbody class="allData">

                <?php
                  $no = 0;
                ?>

                  @foreach ($coupons as $coupon)

                    <tr>
                      <th scope="row">{{++$no}}</th>
                      <td><a href="/userProfile/{{$coupon->id}}" style="text-decoration: none;"><img src="../image/userProfile/{{App\Http\Controllers\couponController::profile($coupon->user)}}" style="width: 30px; height: 30px;" alt=""> {{ $coupon->user }}</a></td>
                      <td >{{ $coupon->coupon }}</td>
                      <td>{{ $coupon->cb_date }}</td>

                      </tr>
                  @endforeach
                  @if ($no == 0)
                      <tr style="text-align: center;"><td class="not-found" colspan="7"><h6>No Coupons</h6></td></tr>
                  @endif
              </tbody>
              </table>
          </div>

        </div>
      </section>

  </main><!-- End #main -->

@endsection
