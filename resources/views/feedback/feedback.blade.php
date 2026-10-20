@extends('layouts.app')
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<title>Feedback</title>
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
          <h2 class="d-flex">Feedback
          </h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Feedback</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->


    <section id="portfolio" class="portfolio">
        <div class="container">

          {{-- <div class="row se" data-aos="fade-in">
            <div class="col-xxl-4 col-md-4 p-1 search-box" style="margin-top: -50px; max-width: 280px;">
                <input class="ml-3 form-control" id="search" type="text" placeholder="Search ..." name="search movies" value="" style="border: none;">
                <button class="search-btn p-2" onclick="searchQuery()" title="Search" type="submit" style="right: 0; top: 8; position: absolute;"><i class="fa fa-search"></i></button>
            </div>
          </div> --}}

          <div class="" data-aos="fade-up" style="margin-top: -100px;">

            @if (Auth::user()->role == 'Admin')
                <!-- ======= Testimonials Section ======= -->
                <section id="testimonials" class="testimonials portfolio section-bg" style="">
                    <div class="container">

                        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
                            <div class="swiper-wrapper">
                                <?php $no = 0; ?>
                              @foreach ($feedbacks as $feedback)
                                <?php $no++; ?>
                                <div class="swiper-slide">
                                  <div class="testimonial-item">
                                  <p>
                                      <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                                      {{$feedback->message}}
                                      <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                                  </p>
                                  <img src="../image/userProfile/{{$feedback->profile}}" class="testimonial-img" alt="">
                                  <h3>{{$feedback->user}}</h3>
                                  </div>
                              </div><!-- End testimonial item -->
                              @endforeach
                              </div>
                              @if ($no == 0)
                                <span style="margin-left: 40%;">No feedbacks yet</span>
                              @endif
                            <div class="swiper-pagination" style=""></div>
                          </div>

                    </div>
                </section><!-- End Testimonials Section -->
            </div>
            @endif

        </div>
      </section>
  </main><!-- End #main -->
@endsection

<script>
    function deleteCatagory(){
        if(!confirm("Are You Sure to delete this catagories?"))
        event.preventDefault();
    }
</script>


