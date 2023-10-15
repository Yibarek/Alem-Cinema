@extends('layouts.app')

<title>Alem Cinema</title>
<script>
    var header = document.getElementById('header');
    header.style.background = rgba(0, 0, 0, 0);
</script>
@section('content')
<script>
    document.getElementById('header').style.background = rgba(0, 0, 0, 0.1);
</script>

  <div style="width: 100%; margin-top: -2%;">
    <div class="msg" style="position: fixed">
        @if (session('success'))
            <script> document.location.href = "#portfolio"; </script>
            <div class="alert alert-success alert-dismissible fade show msg" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                <i class="msg-c">{{session('success')}}</i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('danger'))
        <script> document.location.href = "#testimonials"; </script>
            <div class="alert alert-danger alert-dismissible fade show msg" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{session('danger')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
    @php
        $id = '1';
    @endphp
    <div class="container">
      <div class="row">
        <div class="col-lg-6 pt-5 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h1 class="heroh1" style="color: #1a69aa; font-size: 70px;">Welcome to Alem Cinema</h1>
            <h3 style="color: #1a69aa;">Buy tickets online. Have special time with our movies.</h3>
          <div data-aos="fade-up" data-aos-delay="7">
            <a href="#portfolio" class="btn-get-started scrollto">Movies <i class="bx bx-chevrons-down"></i></a>
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="fade-left" data-aos-delay="200" style="margin-top: 35px; ">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="float: right; max-width: 440px; max-height: 600px;">
                <div class="carousel-inner">
                    @foreach ($movies as $m)
                        <div class="carousel-item @if ($id=='1') active <?php $id='0' ?> @endif" style="text-align: center; width: 100%;">
                            <img src="../image/posters/{{$m->poster}}" class="d-block w-100" alt="...">

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
      </div>
    </div>

  </section><!-- End Hero -->

  <section id="main" style="width: 100%; margin: auto; margin-top: 10px;">

    <section id="portfolio" class="portfolio">
        <div class="container">
            <div class="section-title" data-aos="fade-in" data-aos-delay="100">
                <h2>Most-Liked</h2>
            </div>

          <div class="row portfolio-container1" data-aos="fade-up" style="margin-top: 0">
            @php
                $most_liked = 0;
            @endphp
            @foreach ($most_likes as $most_like)
                @php
                    $most_liked++;
                @endphp
                <div class="col-lg-3 col-md-8 portfolio-item">
                    <div class="portfolio-wrap" style="max-height: 370px; min-height: 370px;">
                    <label class="movie_title">{{$most_like->title}}</label>
                    <img src="../image/posters/{{$most_like->poster}}" class="img-fluid" alt="" style="max-height: 370px; min-height: 370px;">
                    <div class="portfolio-links">
                        <a href="../image/posters/{{$most_like->poster}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="{{$most_like->title}}"><i class="bx bx-zoom-in"></i></a>
                        {{-- <a href="#" data-bs-toggle="modal" data-bs-target="#viewTrailer{{$most_like->id}}" title="Play Trailer"><i class="bx bx-play"></i></a> --}}
                        <a href="/detailM/{{$most_like->id}}" title="More Details"><i class="bx bx-movie-play"></i></a>
                    </div>
                    </div>
                    <div class="">
                    <label class="p-2" data-gallery="portfolioGallery" class="portfolio-lightbox" title="{{$most_like->title}}"><i class="bx bx-show" style="color: #3287cc"></i> view  <strong>{{$most_like->views}}</strong></label>
                    <a href="/like/{{$most_like->id}}/welcome" style="color: #3287cc" title="Likes">
                        @auth
                            @if (App\Http\Controllers\movieController::userLike($most_like->id) )
                                <i class=" bx bxs-like"></i>
                            @else
                                <i class=" bx bx-like"></i>
                            @endif
                        @else
                            <i class=" bx bx-like"></i>
                        @endauth
                    </a>  <strong>{{$most_like->likes}}</strong></label>
                    </div>
                </div>
            @endforeach
            @if ($most_liked == 0)
                <label class="movie_title" style="text-align: center; margin-top: -50px;;">No Movies has like now</label>
            @endif
          </div>
        </section>

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio" style="margin-top: -100px;">
        <div class="container">

          <div class="section-title" data-aos="fade-in" data-aos-delay="100">
            <h2>Movies</h2>
          </div>

          <div class="row" data-aos="fade-in">
            <div class="col-lg-12 d-flex justify-content-center">
              <ul id="portfolio-flters">
                <li data-filter="*" class="filter-active">All</li>
                <li data-filter=".today">Today</li>
                <li data-filter=".comming-soon">Upcomming</li>
                <li data-filter=".passed">Passed</li>
              </ul>
            </div>
          </div>

          <div class="row portfolio-container" data-aos="fade-up">
            @php
                $movie_list = 0;
            @endphp
            @foreach ($movies as $movie)
                @php
                    $movie_list++;
                    $filter = '';
                    if(App\Http\Controllers\movieController::checkTodays($movie->id) == true){
                        $filter = 'today';
                    }
                    elseif((strtotime($movie->show_date) > strtotime("now"))) {
                        $filter = 'comming-soon';
                    }elseif (strtotime($movie->show_date) < strtotime("now")) {
                        $filter = 'passed';
                    }
                @endphp

                <div class="col-lg-3 col-md-9 portfolio-item {{$filter}}">
                    <div class="portfolio-wrap" style="max-height: 370px; min-height: 370px;">
                    <label class="movie_title">{{$movie->title}}</label>
                    <img src="../image/posters/{{$movie->poster}}" class="img-fluid" alt="" style="max-height: 370px; min-height: 370px;">
                    <div class="portfolio-links">
                        <a href="../image/posters/{{$movie->poster}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="{{$movie->title}}"><i class="bx bx-zoom-in"></i></a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#viewTrailer{{$movie->id}}" title="Play Trailer"><i class="bx bx-play"></i></a>
                        <a href="/detailM/{{$movie->id}}" title="More Details"><i class="bx bx-movie-play"></i></a>
                    </div>
                    </div>
                    <div class="">
                    <label class="p-2" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Views"><i class="bx bx-show" style="color: #3287cc"></i>   <strong title="Views">{{$movie->views}}</strong></label>
                    <a href="/like/{{$movie->id}}/welcome" style="color: #3287cc" title="Likes">
                        @auth
                            @if (App\Http\Controllers\movieController::userLike($movie->id))
                                <i class=" bx bxs-like"></i>
                            @else
                                <i class=" bx bx-like"></i>
                            @endif
                        @else
                            <i class=" bx bx-like"></i>
                        @endauth
                    </a>  <strong>{{$movie->likes}}</strong></label>
                    @auth
                        @if (App\Http\Controllers\ticketController::tickets($movie->id))
                            <label class="p-2" title=""><i class="bi bi-ticket-fill" style="color: #3287cc"></i>   <strong>{{$movie->tickets}}</strong>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#ticket{{App\Http\Controllers\ticketController::ticketid($movie->id)}}" class="bi bi-check-circle-fill" style="color: #3287cc"></a>
                            @if (App\Http\Controllers\ticketController::ticketTime($movie->title))
                                <a href="/cancelTicket/{{$movie->id}}/movie" title="Cancel Ticket" class="bi bi-x-circle-fill" style="color: #ec7168"></a>
                            @endif
                        @else
                            <label class="p-2" title=""><i class="bi bi-ticket" style="color: #3287cc"></i>   <strong>{{$movie->tickets}}</strong>
                            @if (App\Http\Controllers\ticketController::ticketTime($movie->title))
                                <a href="/seats/{{$movie->id}}" class="get-btn p-1" type="submit">Buy Ticket</a>
                            @endif
                        @endif
                    @else
                        <label class="p-2" title=""><i class="bi bi-ticket" style="color: #3287cc"></i>   <strong>{{$movie->tickets}}</strong>
                        @if (App\Http\Controllers\ticketController::ticketTime($movie->title))
                            <a href="/seats/{{$movie->id}}" class="get-btn p-1" type="submit">Buy Ticket</a>
                        @endif
                    @endauth
                    </div>
                </div>
            @endforeach
            @if ($movie_list == 0)
                <label class="movie_title" style="text-align: center; margin-top: 0px;">No Movie posts are avaliable.</label>
            @endif
          </div>

        </div>
      </section><!-- End Portfolio Section -->

    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts  section-bg">
      <div class="container">

        <div class="row no-gutters">

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="bi bi-emoji-smile"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{$users}}" data-purecounter-duration="1" class="purecounter"></span>
              <p><strong>Users</strong></p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="bx bx-movie-play" style="margin-top: 20px;"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{$theatres}}" data-purecounter-duration="1" class="purecounter"></span>
              <p><strong>Theatre</strong></p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="bx bx-film" style="margin-top: 20px;"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{$films}}" data-purecounter-duration="1" class="purecounter"></span>
              <p><strong>Films</strong></p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="bx bx-show" style="margin-top: 20px;"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{$shows}}" data-purecounter-duration="1" class="purecounter"></span>
              <p><strong>Shows</strong></p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Counts Section -->

    <!-- ======= Cta Section ======= -->
    <section id="cta" class="cta">
      <div class="container" data-aos="zoom-in">

        <div class="text-center">
          <h3>Live movie</h3>
          <p> Watch what movies are showing NOW. Book your ticket and enjoy watching our movies. Get the schedule, pick your choice and make a reserve your sit.</p>
          <a class="cta-btn" href="#">Now Showing</a>
        </div>

      </div>
    </section><!-- End Cta Section -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials section-bg" style="height: 650px;">
      <div class="container">

        <div class="section-title" data-aos="fade-in" data-aos-delay="100">
          <h2>Feedbacks</h2>
          <p>Read what people say about us.</p>
        </div>

        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">
            @foreach ($feedbacks as $feedback)
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
          <div class="swiper-pagination" style="margin-top: -120px;"></div>
        </div>

      </div>
    </section><!-- End Testimonials Section -->

  </section><!-- End #main -->
  </div>

{{-- Trailer modal start--}}
@foreach ($movies as $movie)
<div style="height: 650px; width: 100%; margin: auto; display: none;"
class="modal fade" id="viewTrailer{{$movie->id}}" data-bs-backdrop="static" data-bs-keyboard="false"
tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 70%;">
    <div class="modal-content" style="overflow: auto; height: 600px;  margin-left: 100px">
        <div class="modal-header bg-black d-flex" style="height: 40px; color: #bbb;">
            <?php $team_logo = ''; ?>

            <div class="modal-title page_title" id="staticBackdropLabel">
                {{$movie->title}}
            </div>
            <span style=""> </span>

            <button type="button" style="color: #bbb;" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"><a href="#" style="color: #fff; margin-left: -30px;">Close</a></button>

        </div>
        <?php $i=1; ?>
        <div class="modal-body bg-black" style=" height: fit-content;">
            <div class="container1 show-controls">
                <video controls src="../video/{{$movie->trailer}}"></video>
            </div>
        </div>
    </div>
</div>
</div>
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@endforeach

<script src="assets/js/player.js"></script>
{{-- modal end--}}

{{-- Ticket modal start--}}
@foreach ($movies as $movie)
<div style="height: 650px; width: 100%; margin: auto; display: none;"
class="modal fade" id="buyTicket{{$movie->id}}" data-bs-backdrop="static" data-bs-keyboard="false"
tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 50%;">
    <div class="modal-content" style="overflow: auto; height: 300px;  margin-left: 100px">
        <div class="modal-header get-btn d-flex" style="height: 40px; color: #fff; background-color: #3287cc;">
            <?php $team_logo = ''; ?>

            <div class="modal-title page_title" id="staticBackdropLabel">
                {{$movie->title}} ///--->>> <strong>Buy Ticket</strong>
            </div>
            <span style=""> </span>

            <button type="button" title="close" style="color: #3287cc;" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"><a href="#" style="color: #fff; margin-left: -30px;"></a></button>

        </div>
        <?php $i=1; ?>
        <div class="modal-body" style=" height: fit-content;">
            <form action="/buyTicket/{{$movie->id}}" method="POST">
                @csrf
                <table>
                    <tr>
                        <div>
                            <td class="p-2">
                                <label >Ticket Type</label>
                        </td>
                            <td class="p-2" >
                                <input onclick="selectType()" required class="form-check-input"  type="radio"  name ="radio" id ="vip" > VIP: <strong>{{$movie->vip}}.00 ETB</strong></input>
                                <input onclick="selectType()" required class="form-check-input" style="margin-left: 60px;" type="radio"  name ="radio" id ="normal" > Normal: <strong>{{$movie->normal}}.00 ETB</strong></input>
                            </td>
                        </div>
                    </tr>

                    <tr>
                        <div>
                            <td class="p-2">
                                <label >Seat</label>
                        </td>
                            <td class="p-2" >
                                <input required class="form-control"  type="number" name ="chairNo" id ="chairNo" >
                            </td>
                        </div>
                    </tr>

                    <tr>
                        <td></td>
                        <td><input  type="text" style="display: none; color: #444; border: none; cursor: none;" id="radio_value" name="radio_value" value=""></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;"><input type="submit" style="border: rgb(27, 134, 167);" value="Order Ticket" class="get-btn"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
</div>
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@endforeach

<script src="assets/js/player.js"></script>
{{-- modal end--}}


{{-- TICKET--}}
@foreach ($Tickets as $Ticket)  `
<div style="height: 100%; width: 100%; margin: auto; display: none;"
    class="modal fade" id="ticket{{$Ticket->id}}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 15%;">
        <div class="modal-content" style="overflow: auto; height: 80%;">
            <div class="modal-header d-flex" style="height: 43px;">
                <?php $team_logo = ''; ?>

                {{-- <div class="d-flex justify-content-between" style="margin-left: 50px; margin-top: 5px"> --}}
                    {{-- <a href="" class="get-btn" style="">Print</a>
                    <button style="font-size: 20px; " type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                {{-- </div> --}}
                <script type="text/javascript">
                    function PrintDiv{{$Ticket->id}}() {
                       var divToPrint{{$Ticket->id}} = document.getElementById('divToPrint{{$Ticket->id}}');
                       var popupWin{{$Ticket->id}} = window.open('', 'width=350, height=400');
                       popupWin{{$Ticket->id}}.document.open();
                       popupWin{{$Ticket->id}}.document.write('<html><body onload="window.print()">' + divToPrint{{$Ticket->id}}.innerHTML + '</html>');
                        popupWin{{$Ticket->id}}.document.close();
                            }
                 </script>

                <div id="divToPrint{{$Ticket->id}}" style="display:none; width: 350px;">
                  <div style="width:300px; height:300px; background-color:rgb(0, 0, 0, 0.1); border: solid 1px gray;">
                    <?php $mov ='';?>

                    @foreach ($movies as $movie)
                        @if ($movie->id == $Ticket->movie)
                            <?php $mov =$movie->title;?>
                        @endif
                    @endforeach
                        <?php  echo "<table class='pl-5' style='width: 300px; margin: auto;'>
                            <tbody style='width: 300px;'>
                                <tr style='width: 300px;'>
                                    <h5 style=' font-weight: 600; margin-left: 100px;'>           Cinima Rukiya</h5>
                                </tr>
                                <tr style='width: 50%; margin: auto;'>
                                    <div>
                                        <td class='p-2' style='width: 35%;'>
                                            <label >Ticket ID: </label>
                                        </td>
                                        <td class='p-2' style='width: 65%;' >
                                            <span>$Ticket->id</span>
                                        </td>

                                    </div>
                                </tr>
                                <tr>
                                    <div>
                                        <td class='p-2'>
                                            <label >User: </label>
                                        </td>
                                        <td class='p-2' >
                                            <span>$Ticket->user</span>
                                        </td>

                                    </div>
                                </tr>
                                <tr>
                                    <div>
                                        <td class='p-2'>
                                            <label >Movie: </label>
                                        </td>
                                        <td class='p-2' >
                                            <span>$mov</span>
                                        </td>
                                    </div>
                                </tr>
                                <tr>
                                    <div>
                                        <td class='p-2'>
                                            <label >Seats: </label>
                                        </td>
                                        <td class='p-2' >
                                            <span>$Ticket->place_no</span>
                                        </td>

                                    </div>
                                </tr>
                                <tr>
                                    <div>
                                        <td class='p-2'>
                                            <label >Chairs: </label>
                                        </td>
                                        <td class='p-2' >
                                            <span>$Ticket->chairs</span>
                                        </td>

                                    </div>
                                </tr>
                                <tr>
                                    <div>
                                        <td class='p-2'>
                                            <label >Amount: </label>
                                        </td>
                                        <td class='p-2' >
                                            <span>$Ticket->amount</span>
                                        </td>

                                    </div>
                                </tr>
                                <tr>
                                    <div>
                                        <td class='p-2'>
                                            <label >Date: </label>
                                        </td>
                                        <td class='p-2' >
                                            <span>$Ticket->created_at</span>
                                        </td>

                                    </div>
                                </tr>
                                <tr>
                                    <td colspan='2' style='text-align: center;'></td>
                                </tr>
                            </tbody>
                        </table>"; ?>
                  </div>
                </div>
                <div>
                  <input class="get-btn" type="button" value="print" onclick="PrintDiv{{$Ticket->id}}();" />
                </div>
                <button style="font-size: 20px; " type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            {{-- </div> --}}
        </div>

        <?php $i=1; ?>
        <div class="modal-body p-3 first" id="t{{$Ticket->id}}" style=" height: 80%; margin-top: 25px;">
            <table class="pl-5" style="width: 95%; margin: auto;">
                    <tbody>
                        <tr>
                            <h5 style="text-align: center; font-weight: 600;">Cinima Rukiya</h5>
                        </tr>
                        <tr style="width: 100%; margin: auto;">
                            <div>
                                <td class="p-2" style="width: 35%;">
                                    <label >Ticket ID: </label>
                                </td>
                                <td class="p-2" style="width: 65%;" >
                                    <span>{{$Ticket->id}}</span>
                                </td>

                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >User: </label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->user}}</span>
                                </td>

                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Movie: </label>
                                </td>
                                <td class="p-2" >
                                    @foreach ($movies as $movie)
                                        @if ($movie->id == $Ticket->movie)
                                            <span>{{$movie->title}}</span>
                                        @endif
                                    @endforeach
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Seats</label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->place_no}}</span>
                                </td>

                            </div>
                        </tr>

                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Chairs: </label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->chairs}}</span>
                                </td>

                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Date: </label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->created_at}}</span>
                                </td>

                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Amount</label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->amount}}</span>
                                </td>

                            </div>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"></td>
                        </tr>
                    </tbody>
                </table>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@endforeach
{{-- /TICKET--}}

<script type="text/javascript">
    function selectType(){
        var rd1 = document.getElementById("vip");
        var rd2 = document.getElementById("normal");

        if (rd1.checked == true) {
            document.getElementById('radio_value').value = 'vip';
        }
        else if (rd2.checked == true) {
            document.getElementById('radio_value').value = 'normal';
        }

    }
</script>

@endsection
