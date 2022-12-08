@extends('layouts.app')
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
  background-color: #b1b7b9;
  /* display: flex; */
  flex-direction: column;
  color: #333;
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
  height: 16px;
  width: 26px;
  margin: 3px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.seat.selected {
  background-color: #42b6e4;
}

.selected {
  background-color: #42b6e4;
}

.seat.vip {
  background-color: #55264f;
}

.seat.occupied {
  background-color: #911;
}
.occupied {
  background-color: #911;
  height: 16px;
  width: 26px;
  margin: 3px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
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

<title>Seats</title>
@section('content')
    @foreach ($movies as $movie)
    <div style="margin-top: 50px; width: 100%; background-color: #c1c7c9; padding-bottom: 100px;">
    <form action="/buyTicket/{{$movie->id}}" method="POST" style="width: 100%;">
        @csrf
    <div style="height: fit-content; width: 100%; margin: auto; "
        class="" id="buyTicket{{$movie->id}}" >
    <div class="" style="width: 100%; margin: auto;">
        <div class="" style="overflow: hidden;  margin-left: 100px">

            <?php $i=1; ?>
            <div class="" style=" height: fit-content; margin: auto; width: 60%;">
                <table>
                    <tr>
                        <div>

                            <td class="p-2" >
                                <span name ="radio" id ="vip" value="{{$movie->vip}}"> VIP: <strong>{{$movie->vip}}.00 ETB</strong></span>
                                <span style="margin-left: 60px;" name ="radio" id ="normal" value="{{$movie->normal}}"> Normal: <strong>{{$movie->normal}}.00 ETB</strong></span>
                                <span style="margin-left: 50px;"> Current balance: <i style="font-weight: 600;">{{Auth::user()->account}}</i></span>
                              </td>
                        </div>
                    </tr>
                </table>
            </div>


            <div class="container" style="width: 60%; margin: auto;">
              <ul class="showcase" style="width: 70%;">
                  <li>
                    <div class="seat vip"></div>
                    <small>Vip</small>
                </li>

                <li>
                    <div class="seat"></div>
                    <small>Normal</small>
                </li>

                <li>
                    <div class="seat selected"></div>
                    <small>Selected</small>
                </li>

                <li>
                    <div class="seat occupied"></div>
                    <small>Occupied</small>
                </li>
            </ul>
                <div class="screen" style="width: 70%; height: 40px; margin-top: 0px; margin-bottom: 10px;"></div>
                @php
                    $z=1;
                    $k="0";
                @endphp
                @for ($i=1; $i<=16; $i++)
                    <div class="row">
                        @for ($j=1; $j<=16; $j++)
                        <div
                            @if (App\Http\Controllers\ticketController::occupied($movie->id, $z) == true)
                                class="occupied"
                                @if ($z==2 || $z==18 || $z==34 || $z==50 || $z==66 || $z==82 || $z==98 || $z==114 || $z==130 || $z==146 || $z==162 || $z==178 || $z==194 || $z==210 || $z==226 || $z==242)
                                    style="margin-right: 18px; height: 16px; width: 26px;"
                                    @php
                                        $k="1";
                                    @endphp
                                @else
                                    @php
                                        $k="0";
                                    @endphp
                                @endif
                                @if (($z==3 )|| $z==19 || $z==35 || $z==51 || $z==67 || $z==83 || $z==99 || $z==115 || $z==131 || $z==147 || $z==163 || $z==179 || $z==195 || $z==211 || $z==227 || $z==243 ||
                                     $z==15 || $z==31 || $z==47 || $z==63 || $z==79 || $z==95 || $z==111 || $z==127 || $z==143 || $z==159 || $z==175 || $z==191 || $z==207 || $z==223 || $z==239 || $z==255)
                                    @if ($k=="0")
                                        style="margin-left: 18px; height: 16px; width: 26px;"
                                    @endif
                                @endif
                            @endif

                            class="seat @if ($i<=2)
                                vip
                            @endif p-1" id="{{$z}}" title="{{$z}}" style="width: 26px;" onclick="z{{$z}}();"
                            @if ($i<=2)
                              style="background-color: #55264f;"
                            @endif

                            ></div>
                            <input  id="in{{$z}}" name="in{{$z}}" style="display: none;" value="x">
                            @if (App\Http\Controllers\ticketController::occupied($movie->id, $z) == false)
                            <script>
                                function z{{$z}}() {
                                  var seat{{$z}} = document.getElementById({{$z}});
                                  var in{{$z}} = document.getElementById("in{{$z}}");
                                  var selected{{$z}} = document.getElementById("selected");
                                  var cost{{$z}} = document.getElementById("costs");
                                  var inputSelected{{$z}} = document.getElementById("inputSelected");
                                  var inputcost{{$z}} = document.getElementById("inputCosts");
                                  var br{{$z}} = '';

                                  if (in{{$z}}.value == "x") {

                                    var totalSelected{{$z}} = parseInt(selected{{$z}}.innerHTML) + 1;
                                    br{{$z}} = '';
                                    if ({{$i}} <= 2) {
                                      if ((parseInt(cost{{$z}}.innerHTML) + {{$movie->vip}}) > {{Auth::user()->account}}) {
                                        alert("Inceficeint balance. Please buy coupon to have more tickets!");
                                        br{{$z}} = "br{{$z}}";
                                      }
                                      if (br{{$z}} == '') {
                                        var costs{{$z}} = parseInt(cost{{$z}}.innerHTML) + {{$movie->vip}};
                                        cost{{$z}}.innerHTML = costs{{$z}};
                                        inputcost{{$z}}.value = costs{{$z}};
                                        in{{$z}}.value = "{{$z}}";
                                        selected{{$z}}.innerHTML = totalSelected{{$z}};
                                        inputSelected{{$z}}.value = totalSelected{{$z}};
                                        seat{{$z}}.classList.remove('vip');
                                        seat{{$z}}.classList.add('selected');
                                      }
                                    }
                                    else{
                                      if ((parseInt(cost{{$z}}.innerHTML) + {{$movie->normal}}) > {{Auth::user()->account}}) {
                                        alert("Inceficeint balance. Please buy coupon to have more tickets!");
                                        br{{$z}} = "br{{$z}}";
                                      }
                                      if (br{{$z}} == '') {
                                        var costs{{$z}} = parseInt(cost{{$z}}.innerHTML) + {{$movie->normal}};
                                        cost{{$z}}.innerHTML = costs{{$z}};
                                        inputcost{{$z}}.value = costs{{$z}};
                                        in{{$z}}.value = "{{$z}}";
                                        selected{{$z}}.innerHTML = totalSelected{{$z}};
                                        inputSelected{{$z}}.value = totalSelected{{$z}};
                                        seat{{$z}}.classList.add('selected');
                                      }
                                    }
                                  }
                                  else{
                                    var totalSelected1{{$z}} = parseInt(selected{{$z}}.innerHTML) - 1;
                                    if ({{$i}} <= 2) {
                                      seat{{$z}}.classList.add('vip');
                                      var costs3{{$z}} = parseInt(cost{{$z}}.innerHTML) - {{$movie->vip}};
                                      cost{{$z}}.innerHTML = costs3{{$z}};
                                      inputcost{{$z}}.value = costs{{$z}};
                                    }
                                    else{
                                      var costs4{{$z}} = parseInt(cost{{$z}}.innerHTML) - {{$movie->normal}};
                                      cost{{$z}}.innerHTML = costs4{{$z}};
                                    }
                                    seat{{$z}}.classList.remove('selected');

                                    in{{$z}}.value = "x";
                                    selected{{$z}}.innerHTML = totalSelected1{{$z}};
                                    inputSelected{{$z}}.value = totalSelected1{{$z}};
                                  }
                                }
                              </script>
                            @endif

                            @php
                                $z++;
                            @endphp
                        @endfor
                    </div>
                    @if ($i==10 || $i==2)
                        <div class="row" style="height: 10px;"><br></div>
                    @endif
                @endfor
            </div>
        </div>

        <script>
          var k=1;
          for (let i = 1; i <= 16; i++) {
            for (let j = 1; j <= 16; j++) {
              var seat = document.getElementById(k);
              if (k.) {
                1
              }
              k++;
            }
          }
        </script>
        <div style="width: 40%; margin: auto;">
            <p class="text">
              <input type="text" id="total_seat" style="display: none;" value="1">
              <input type="text" id="total_birr" style="display: none;" value="2">
                You have selected <span id="count"><strong style="color: #333;"  id="selected">0</strong></span> price for all seats $<span id="total"><strong style="color: #333;" id="costs">0</strong></span>
                </p>
                <input type="text" id="inputSelected" name="inputSelected" style="display: none;" value="1">
                <input type="text" id="inputCosts" name="inputCosts" style="display: none;" value="1">
                <div>
                    <input type="submit" style="border: rgb(27, 134, 167);" value="Order Ticket" class="get-btn">
                </div>
        </div>
    </form>
        <script src="script.js"></script>
        </div>
        </div>

    <link href="assets/css/player.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    @endforeach

    <script src="assets/js/player.js"></script>

@endsection
