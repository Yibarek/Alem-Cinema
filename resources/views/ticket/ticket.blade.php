-@extends('layouts.app')
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<title>Tickets</title>
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
          <h2 class="d-flex">Tickets
            @if (Auth::user()->role == 'Ticket Controller')
                <a title="Add New User" class="ml-2 d-flex" href="#" data-bs-toggle="modal" data-bs-target="#createTicket">
                    <i class="" style="color: #fff">A</i>
                    <div style="margin-top: 1px;"><i class="mt-3 rounded-circle get-btn-circle ri ri-add-fill"></i>
                    </div>
                </a>
            @endif
          </h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Tickets</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->


    <section id="portfolio" class="portfolio">
        <div class="container">

          <div class="row se" data-aos="fade-in">
            <div class="col-xxl-4 col-md-4 p-1 search-box" style="margin-top: -50px; max-width: 280px;">
                <input class="ml-3 form-control" onkeyup="searchTicket()" onkeypress="searchTicket()" onkeydown="searchTicket()" id="search_input" type="text" placeholder="Search User..." name="search movies" value="" style="border: none;">
                <button class="search-btn p-2" onclick="searchTicket()" title="Search" type="submit" style="right: 0; top: 8; position: absolute;"><i class="ri ri-search-line"></i></button>
            </div>
          </div>

          <div class="" data-aos="fade-up" style="margin-top: 0;">
            <table class="table table-borderless datatable table-hover table-sm all-tables">
                <thead>
                  <tr style="height: 35px; background-color: #fafafa;">
                    <th style="width: 4%" scope="col">#</th>
                    <th style="width: 15%" scope="col">User</th>
                    <th style="width: 15%" scope="col">Movie</th>
                    <th style="width: 15%" scope="col">Seats</th>
                    <th style="width: 15%" scope="col">Chairs</th>
                    <th style="width: 15%" scope="col">Amount</th>
                  </tr>
                </thead>
                <tbody class="allData" id="allData">

                <?php
                  $no = 0;

                ?>

                  @foreach ($Tickets as $Ticket)

                  @if (Auth::user()->role == 'Ticket Controller' || Auth::user()->role == 'Admin' || Auth::user()->username == $Ticket->user)
                    <tr>
                      <th scope="row"><a href="#" data-bs-toggle="modal" data-bs-target="#ticket{{$Ticket->id}}">{{$Ticket->id}}</a></th>
                      <td><a href="/userProfile/{{$Ticket->id}}" style="text-decoration: none;"><img src="../image/userProfile/{{App\Http\Controllers\TicketController::profile($Ticket->user)}}" style="width: 30px; height: 30px;" alt=""> {{ $Ticket->user }}</a></td>
                      <?php $mov ='';?>

                        @foreach ($movies as $movie)
                            @if ($movie->id == $Ticket->movie)
                                <?php $mov =$movie->title;?>
                            @endif
                        @endforeach

                      <td >{{ $mov }}</td>
                      <td>{{ $Ticket->place_no }}</td>
                      <td>{{ $Ticket->chairs }}</td>
                      <td>{{ $Ticket->amount }}</td>
                       @php
                           $no++;
                       @endphp
                      </tr>
                  @endif
                  @endforeach
                  @if ($no == 0)
                      <tr style="text-align: center;"><td class="not-found" colspan="7"><h6>No Tickets</h6></td></tr>
                  @endif
              </tbody>
              <tbody class="searchData"></tbody>
                  <tfoot>
                      <tr>

                      </tr>
                          <td colspan="4">
                              {{-- {{ $Tickets->onEachSide(1)->links()}} --}}
                        </td>
                      </tr>
                  </tfoot>
              </table>
          </div>

        </div>
      </section>

  </main><!-- End #main -->

{{-- CREATE Ticket--}}
<div style="height: 100%; width: 100%; margin: auto; display: none;"
    class="modal fade" id="createTicket" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 60%;">
        <div class="modal-content" style="overflow: auto; height: 70%;">
            <div class="modal-header d-flex" style="height: 40px;">
                <?php $team_logo = ''; ?>

                <div class="modal-title page_title" id="staticBackdropLabel">
                    <i class="bi bi-ticket-fill " style="color: #235699; font-size: 18px;"></i> Create Ticket
                </div>
                <span style="margin-left: 10px; margin-top: 2px;"> </span>

                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <?php $i=1; ?>
            <div class="modal-body p-3" style=" height: 80%; margin-top: 25px;">
                <div class="pl-5" style="width: 85%; margin: auto;">
                    <form action="/403" method="get">
                        @csrf
                    <table class="pl-5" style="width: 100%; margin: auto;">

                        <tr style="width: 100%; margin: auto;">
                            <div>
                                <td class="p-2" style="width: 35%;">
                                    <label >User</label>
                                </td>
                                <td class="p-2" >
                                    <input onchange="ticketType()" class="selectVal" name="user" id="user" style=" color: #333; width: 100%; height: 40px; border: 1px solid #e1e1e1;">
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Movie</label>
                                </td>
                                <td class="p-2" >
                                    <select onchange="ticketType()" class="selectVal" name="selectVal" id="selectVal" style=" color: #333; width: 100%; height: 40px; border: 1px solid #e1e1e1;">
                                    @foreach ($movies as $movie)
                                        <option required class="form-control" value="{{$movie->title}}">
                                            <img src="../image/posters/{{$movie->poster}}" width="30" height="40" style="margin-right: 10px;" alt="">{{$movie->title}}</option>
                                    @endforeach
                                    </select>
                                </td>
                            </div>
                        </tr>

                        <tr>
                            <td><input  type="text" style=" color: #fff; border: none; cursor: none;" id="radio_value" name="radio_value" value=""></td>
                            <td></td>
                        <tr>
                            <td colspan="2" style="text-align: center;"><input type="submit" style="border: rgb(27, 134, 167);" value="Continue" class="get-btn"></td>
                        </tr>

                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- /CREATE Ticket--}}

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
                    {{-- <a href="" class="get-btn" style="">Print</a> --}}
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
                      <div style="width:300px; height:300px; background-color:rgb(0, 0, 0, 0.8); border: solid 1px gray;">
                        <?php $mov ='';?>

                        @foreach ($movies as $movie)
                            @if ($movie->id == $Ticket->movie)
                                <?php $mov =$movie->title;?>
                            @endif
                        @endforeach
                            <?php  echo "<table class='pl-5' style='width: 300px; margin: auto;'>
                                <tbody style='width: 300px;'>
                                    <tr style='width: 300px;'>
                                        <h5 style=' font-weight: 600;'>Cinima Rukiya</h5>
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
                                    <label >Seats: </label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->place_no}}</span>
                                </td>

                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Seat No: </label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->chairs}}</span>
                                </td>

                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Amount: </label>
                                </td>
                                <td class="p-2" >
                                    <span>{{$Ticket->amount}}</span>
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

@endsection

<script type="text/javascript">
    function deleteTicket(){
        if(!confirm("Are You Sure to delete this Ticket?"))
        event.preventDefault();
    }

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

    function ticketType(){
        var selected = document.getElementById('selectVal').value;
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                feedback = JSON.parse(this.responseText);
                document.getElementById('vip').value = feedback.vip;
                document.getElementById('vip_amount').innerHTML = feedback.vip;
                document.getElementById('normal').value = feedback.normal;
                document.getElementById('normal_amount').innerHTML = feedback.normal;
            }

        };
        var location = "/ticketValue/"+selected;
        xhttp.open("GET", location, true);
        xhttp.send();

    }

    function searchTicket(){
        var input = document.getElementById('search_input').value;
        var all = document.getElementById('allData');
        var search = document.getElementById('searchData');

        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                result = JSON.parse(this.responseText);
                // console.log(result.output);
                // all.style.display = "none";
                // search.innerHTML(result.output);
                all.innerHTML = result.output;

            }

        };
        var location = "/searchTicket/"+input;
        xhttp.open("GET", location, true);
        xhttp.send();
    }
</script>
