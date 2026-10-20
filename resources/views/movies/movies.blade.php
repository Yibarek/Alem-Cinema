@extends('layouts.app')

<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<title>Movies</title>
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

            <h2 class="d-flex align-items-center">Movies
                @auth
                    @if (Auth::user()->role == 'Multimedia Specialist' || Auth::user()->role == 'Admin')
                        <a title="Add New Movie" class="get-btn btn-sm d-inline-flex align-items-center gap-2 ms-3" style="border-radius: 50px; padding: 6px 16px; font-size: 13.5px; font-weight: 700; text-decoration: none;" href="/addM">
                            <i class="bi bi-plus-lg" style="font-size: 14px; font-weight: 800;"></i> Add New Movie
                        </a>
                    @endif
                @endauth
                <div class="d-flex">
                    <label style="margin-left: 100px; margin-top: 5px; font-size: 17px;">Catagory: </label>
                    <select onchange="catagory()" class="selectVal" id="selectVal" name="user" class="form-check-inline" style="margin-left: 10px; color: #333;  font-size: 17px; width: 150px; height: 30px; border: 1px solid #e1e1e1;">
                        <option value="all" style="color: #333; font-size: 17px;" onclick="location.href('/movieByCatagory/all')">All</option>
                        @foreach ($catagories as $catagory)
                            <option value="{{$catagory->name}}" style="color: #333; font-size: 17px;"
                                @if ($catagory->name == $cat)
                                selected
                            @endif>
                            {{$catagory->name}}</option>
                        @endforeach
                    </select>
                </div>
              </h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Movies</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
        <div class="container">

          <div class="row se d-flex" data-aos="fade-in">
            <div class="col-xxl-4 col-md-4 p-1 search-box" style="margin-top: -50px; max-width: 280px;">
                <input class="ml-3 form-control" onkeypress="searchMovie()" onkeydown="searchMovie()" onkeyup="searchMovie()" id="search_input" type="text" placeholder="Search Movie..." name="search movies" value="" style="border: none;">
                <button class="search-btn p-2" onclick="searchMovie()" title="Search" type="submit" style="right: 5px; top: 8px; position: absolute;"><i style="width: 30px;" class="ri ri-search-line"></i></button>
            </div>
            <script>
                function searchMovie(){
                    var input = document.getElementById('search_input').value;
                    var all = document.getElementById('allData');

                    // alert("0");
                    var xhttp1;
                    xhttp1 = new XMLHttpRequest();
                    xhttp1.onreadystatechange = function() {;
                        if (this.readyState == 4 && this.status == 200) {
                            result1 = JSON.parse(this.responseText);
                            // console.log(result1.output);
                            // all.style.display = "none";
                            // search.innerHTML(result1.output);
                            all.innerHTML = result1.output;
                        }

                    };
                    var location = "/searchMovie/"+input;
                    xhttp1.open("GET", location, true);
                    xhttp1.send();
                }
            </script>
            <div class="col-xxl-4 col-md-4 p-2 search-box d-flex" style="margin-top: 5px; border: none; text-align: center; margin-top: -50px; max-width: 280px;">
                <h4><a href="/today" style="font-weight: 600; font-size: 18px;" class="get-started-btn"> Today </a></h4>
                <h4><a href="/thisWeek"  style="font-weight: 600; font-size: 18px;" class="get-started-btn"> Next 7 Days </a></h4>
            </div>

          </div>

          <div class="row portfolio-container"  id="allData" data-aos="fade-up" style="margin-top: 0">
            @php
                $movie_list = 0;
            @endphp
            @foreach ($movies as $movie)
                @php
                    $movie_list++;
                @endphp
                <div class="col-lg-2 col-md-4 portfolio-item filter-app">
                    <div class="portfolio-wrap" style="max-height: 260px; min-height: 260px; position: relative;">
                        @auth
                            @if (Auth::user()->role == "Multimedia Specialist" || Auth::user()->role == "Admin")
                            <div class="movie-admin-actions">
                                <a href="/editM/{{$movie->id}}" class="action-badge-btn edit" title="Edit Movie">
                                    <i class="bi bi-pencil-square" style="color: #ffffff !important; font-size: 15px;"></i>
                                </a>
                                <a href="/deleteMovie/{{$movie->id}}" class="action-badge-btn delete" onclick="return confirm('Are you sure you want to delete this movie?');" title="Delete Movie">
                                    <i class="bi bi-trash-fill" style="color: #ffffff !important; font-size: 15px;"></i>
                                </a>
                            </div>
                            @endif
                        @endauth

                        <label class="movie_title">{{$movie->title}}</label>
                        <img src="../image/posters/{{$movie->poster}}" class="img-fluid" alt="" style="max-height: 260px; min-height: 260px; width: 100%; object-fit: cover;">
                        <div class="portfolio-links">
                            <a href="../image/posters/{{$movie->poster}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="{{$movie->title}}"><i class="bi bi-zoom-in"></i></a>
                            <a href="#" id="{{$movie->id}}" onclick="view{{$movie->id}}()" data-bs-toggle="modal" data-bs-target="#viewTrailer{{$movie->id}}" title="Play Trailer"><i class="bi bi-play-fill"></i></a>
                            <input type="text" id="reserver" name="reserver" style="display: none;">
                            <a href="/detailM/{{$movie->id}}" title="More Details"><i class="bi bi-info-circle"></i></a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap pt-2 px-1">
                        <div class="d-flex align-items-center gap-2">
                            <label class="p-1 mb-0" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Views"><i class="bi bi-eye" style="color: #f59e0b"></i> <strong id="v{{$movie->id}}">{{$movie->views}}</strong></label>
                            <label class="p-1 mb-0" title="Likes">
                                <a href="/like/{{$movie->id}}/movie" style="color: #f59e0b" title="Likes">
                                    @auth
                                        @if (App\Http\Controllers\movieController::userLike($movie->id))
                                            <i class="bi bi-hand-thumbs-up-fill"></i>
                                        @else
                                            <i class="bi bi-hand-thumbs-up"></i>
                                        @endif
                                    @else
                                        <i class="bi bi-hand-thumbs-up"></i>
                                    @endauth
                                </a> <strong>{{$movie->likes}}</strong>
                            </label>
                            @auth
                                @if (App\Http\Controllers\ticketController::tickets($movie->id))
                                    <label class="p-1 mb-0" title="Purchased Ticket">
                                        <i class="bi bi-ticket-perforated-fill" style="color: #f59e0b"></i> <strong>{{$movie->tickets}}</strong>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#ticket{{App\Http\Controllers\ticketController::ticketid($movie->id)}}" class="bi bi-check-circle-fill text-success" title="View Ticket"></a>
                                        <a href="/cancelTicket/{{$movie->id}}/movie" title="Cancel Ticket" class="bi bi-x-circle-fill text-danger ms-1"></a>
                                    </label>
                                @endif
                            @endauth
                        </div>
                        <div>
                            <a href="/seats/{{$movie->id}}" class="get-btn btn-sm" style="border-radius: 50px; padding: 4px 12px; font-size: 12.5px; text-decoration: none; font-weight: 600;" type="submit">Buy Ticket</a>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($movie_list == 0)
                <label class="movie_title" style="text-align: center;">No Movie posts are avaliable.</label>
            @endif
          </div>
        </section>


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
                                    <h5 style=' font-weight: 600; margin-left: 100px;'>           Alem Cinema</h5>
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
                            <h5 style="text-align: center; font-weight: 600;">Alem Cinema</h5>
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
    function catagory() {
        var selected = document.getElementById('selectVal').value;
        var cat_location = '/movieByCatagory/' + selected;
        location.replace(cat_location);
    }

    function view{{$movie->id}}() {
        alert('{{$movie->id}}');
        var xhttp{{$movie->id}};
        xhttp{{$movie->id}} = new XMLHttpRequest();
        xhttp{{$movie->id}}.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                view{{$movie->id}} = JSON.parse(this.responseText);
                if (view{{$movie->id}} == 1) {
                    var prev{{$movie->id}} = document.getElementById('v{{$movie->id}}';
                    var now{{$movie->id}} = prev{{$movie->id}}.innerHTML + 1;
                    prev{{$movie->id}}.value = now{{$movie->id}};
                }
            }
        };
        var location{{$movie->id}} = "/view/{{$movie->id}}";
        xhttp{{$movie->id}}.open("GET", location{{$movie->id}}, true);
        xhttp{{$movie->id}}.send();
    }
</script>
@endforeach

<script src="assets/js/player.js"></script>
{{-- modal end--}}

<script src="assets/js/player.js"></script>

<script>
    const container = document.querySelector('.container');
const seats = document.querySelectorAll('.row .seat:not(.occupied');
const count = document.getElementById('count');
const total = document.getElementById('total');
const movieSelect = document.getElementById('movie');

populateUI();
let ticketPrice = +movieSelect.value;

// Save selected movie index and price
function setMovieData(movieIndex, moviePrice) {
  localStorage.setItem('selectedMovieIndex', movieIndex);
  localStorage.setItem('selectedMoviePrice', moviePrice);
}

// update total and count
function updateSelectedCount() {
  const selectedSeats = document.querySelectorAll('.row .seat.selected');

  const seatsIndex = [...selectedSeats].map((seat) => [...seats].indexOf(seat));

  localStorage.setItem('selectedSeats', JSON.stringify(seatsIndex));

  //copy selected seats into arr
  // map through array
  //return new array of indexes

  const selectedSeatsCount = selectedSeats.length;

  count.innerText = selectedSeatsCount;
  total.innerText = selectedSeatsCount * ticketPrice;
}

// get data from localstorage and populate ui
function populateUI() {
  const selectedSeats = JSON.parse(localStorage.getItem('selectedSeats'));
  if (selectedSeats !== null && selectedSeats.length > 0) {
    seats.forEach((seat, index) => {
      if (selectedSeats.indexOf(index) > -1) {
        seat.classList.add('selected');
      }
    });
  }

  const selectedMovieIndex = localStorage.getItem('selectedMovieIndex');

  if (selectedMovieIndex !== null) {
    movieSelect.selectedIndex = selectedMovieIndex;
  }
}

// Movie select event
movieSelect.addEventListener('change', (e) => {
  ticketPrice = +e.target.value;
  setMovieData(e.target.selectedIndex, e.target.value);
  updateSelectedCount();
});

// Seat click event
container.addEventListener('click', (e) => {
  if (e.target.classList.contains('seat') && !e.target.classList.contains('occupied')) {
    e.target.classList.toggle('selected');

    updateSelectedCount();
  }
});

// intial count and total
updateSelectedCount();
</script>
<style>
    @import url('https://fonts.googleapis.com/css?family=Lato&display=swap');

* {
  box-sizing: border-box;
}

body {
//   background-color: #242333;
//   display: flex;
  flex-direction: column;
//   color: #fff;
  align-items: center;
  justify-content: center;
  height: 100vh;
  font-family: 'Lato', 'sans-serif';
}

.movie-container {
  margin: 20px 0;
}

.movie-container select {
  background-color: #fff;
  border: 0;
  border-radius: 5px;
  font-size: 14px;
  margin-left: 10px;
  padding: 5px 15px 5px 15px;
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
}

.container {
  perspective: 1000px;
  margin-bottom: 30px;
}

.seat {
  background-color: #444451;
  height: 12px;
  width: 15px;
  margin: 3px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.seat.selected {
  background-color: #6feaf6;
}

.seat.occupied {
  background-color: #fff;
}

.seat:nth-of-type(2) {
  margin-right: 18px;
}

.seat:nth-last-of-type(2) {
  margin-left: 18px;
}

.seat:not(.occupied):hover {
  cursor: pointer;
  transform: scale(1.2);
}

.showcase .seat:not(.occupied):hover {
  cursor: default;
  transform: scale(1);
}

.showcase {
  background-color: rgba(0, 0, 0, 0.1);
  padding: 5px 10px;
  border-radius: 5px;
  color: #777;
  list-style-type: none;
  display: flex;
  justify-content: space-between;
}

.showcase li {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 10px;
}

.showcase li small {
  margin-left: 10px;
}

.row {
  display: flex;
}

.screen {
  background-color: #fff;
  height: 70px;
  width: 100%;
  margin: 15px 0;
  transform: rotateX(-45deg);
  box-shadow: 0 3px 10px rgba(255, 255, 255, 0.75);
}

p.text {
  margin: 5px 0;
}

p.text span {
  color: #6feaf6;
}</style>

@endsection
