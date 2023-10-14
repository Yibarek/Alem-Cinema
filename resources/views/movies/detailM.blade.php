@extends('layouts.app')
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@foreach ($movies as $movie)
    <title>{{$movie->title}}</title>
@endforeach
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
    @foreach ($movies as $movie)
    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>{{$movie->title}}</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li><a href="movie">Movies</a></li>
            <li>Movie detail</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">

        <div class="row gy-4">
          <div class="" style="margin-top: -20px; margin-bottom: -10px;">
                <label class="p-2" data-gallery="portfolioGallery" class="portfolio-lightbox" title="{{$movie->title}}"><i class="bx bx-show" style="color: #3287cc"></i> view  <strong>{{$movie->views}}</strong></label>
                <a href="/like/{{$movie->id}}/detailM" style="color: #3287cc" title="Likes">
                    @auth
                        @if (App\Http\Controllers\movieController::userLike($movie->id))
                            <i class=" bx bxs-like"></i>
                        @else
                            <i class=" bx bx-like"></i>
                        @endif
                    @else
                        <i class=" bx bx-like"></i>
                    @endauth
                </a>  <strong>{{$movie->likes}}</strong>

                @auth
                    @if (App\Http\Controllers\ticketController::tickets($movie->id))
                        <label class="p-2" title=""><i class="bi bi-ticket-fill" style="color: #3287cc"></i>   <strong>{{$movie->tickets}}</strong>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#ticket{{App\Http\Controllers\ticketController::ticketid($movie->id)}}" class="bi bi-check-circle-fill" style="color: #3287cc"></a>
                        {{-- @if (App\Http\Controllers\ticketController::ticketTime($movie->title)) --}}
                            <a href="/cancelTicket/{{$movie->id}}/welcome" title="Cancel Ticket" class="bi bi-x-circle-fill" style="color: #ec7168"> Cancel Ticket</a>
                        {{-- @endif --}}
                    @else
                        <label class="p-2" title=""><i class="bi bi-ticket" style="color: #3287cc"></i>   <strong>{{$movie->tickets}}</strong>
                        {{-- @if (App\Http\Controllers\ticketController::ticketTime($movie->title)) --}}
                            <a href="/seats/{{$movie->id}}" class="get-btn p-1" type="submit">Buy Ticket</a>
                        {{-- @endif --}}
                    @endif
                @else
                <label class="p-2" title=""><i class="bi bi-ticket" style="color: #3287cc"></i>   <strong>{{$movie->tickets}}</strong>
                    @if (App\Http\Controllers\ticketController::ticketTime($movie->title))
                        <a href="/seats/{{$movie->id}}" class="get-btn p-1" type="submit">Buy Ticket</a>
                    @endif
                @endauth
          </div>

          <div class="col-lg-7">
            <div class="container1 show-controls">
                <video controls src="../video/{{$movie->trailer}}"  style="width: 100%; max-width: 500px; height: 100%; max-height: 300px;"></video>
            </div>
            <div class="p-3"></div>
            <div class="portfolio-details-slider swiper" style="max-height: 500px; max-width: 300px;">
                <div class="swiper-pagination"></div>
                <div class="swiper-wrapper align-items-center">

                  <div class="swiper-slide">
                    <img src="../image/posters/{{$movie->poster}}" alt="">
                  </div>

                </div>
              </div>
          </div>

          <div class="col-lg-5">
            <div class="portfolio-info">
              <h3>Movie information</h3>
              <ul>
                <li><strong>Show Schedule</strong>: {{$movie->show_date}} <strong>{{$movie->show_time}}</strong></li>
                <li><strong>Title</strong>: {{$movie->title}}</li>
                <li><strong>Length</strong>: {{$movie->length}}</li>
                <li><strong>Category</strong>: {{$movie->catagory}}</li>
                <li><strong>Producer</strong>: {{$movie->producers}}</li>
                <li><strong>Director</strong>: {{$movie->directors}}</li>
                <li><strong>Actors</strong>: {{$movie->actors}}</li>
                <li><strong>Released Year</strong>: {{$movie->released_year}}</li>
              </ul>
            </div>
            <div class="portfolio-description">
                <?php $description = 'Movie detail (Description)'?>
              <h2>{{$description}}</h2>
              <p>
                {{$movie->description}}
              </p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Portfolio Details Section -->
    @endforeach
  </main><!-- End #main -->


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

<script src="assets/js/player.js"></script>
@endsection
