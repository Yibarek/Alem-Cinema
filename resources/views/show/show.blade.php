@extends('layouts.app')
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<title>Show</title>
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
          <h2 class="d-flex">Show
            @if (Auth::user()->role == 'Multimedia Specialist')
                <a title="Add New User" class="ml-2 d-flex" href="#" data-bs-toggle="modal" data-bs-target="#addShow">
                    <i class="" style="color: #fff">A</i>
                    <div style="margin-top: 1px;"><i class="mt-3 rounded-circle get-btn-circle ri ri-add-fill"></i>
                    </div>
                </a>
            @endif
          </h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Show</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->


    <section id="portfolio" class="portfolio">
        <div class="container">

          <div class="row se" data-aos="fade-in">
            <div class="col-xxl-4 col-md-4 p-1 search-box" style="margin-top: -50px; max-width: 280px;">
                <input class="ml-3 form-control" id="search_input" onkeypress="searchShow()" onkeyup="searchShow()" onkeydown="searchShow()" type="text" placeholder="Search Show..." name="search movies" value="" style="border: none;">
                <button class="search-btn p-2" onclick="searchShow()" title="Search" type="submit" style="right: 0; top: 8; position: absolute;"><i class="ri ri-search-line"></i></button>
            </div>
          </div>

          <div class="" data-aos="fade-up" style="margin-top: 0;">
            <table class="table table-borderless datatable table-hover table-sm all-tables">
                <thead>
                  <tr style="height: 35px; background-color: #fafafa;">
                    <th style="width: 4%" scope="col">#</th>
                    <th style="width: 10%" scope="col">movie</th>
                    <th style="width: 10%" scope="col">Date</th>
                    <th style="width: 5%" scope="col">Time</th>
                    <th style="width: 5%" scope="col">Vip</th>
                    <th style="width: 5%" scope="col">Normal</th>
                    {{-- <th style="width: 10%" scope="col">Up Comming</th> --}}

                    @if (Auth::user()->role == "Multimedia Specialist" || Auth::user()->role == "Multimedia Specialist")
                      <th style="width: 8%" scope="col">Action</th>
                    @endif

                  </tr>
                </thead>
                <tbody class="allData" id="allData">

                <?php
                  $no = 0;
                ?>

                  @foreach ($shows as $show)

                  @if (Auth::user()->role == 'Multimedia Specialist')
                    <tr>
                      <th scope="row">{{++$no}}</th>
                      <td >{{ $show->movie }}</td>
                      <td >{{ $show->show_date }}</td>
                      <td >{{ $show->show_time }}</td>
                      <td >{{ $show->vip }}</td>
                      {{-- <td >{{ $show->max_vip_place_no }}</td> --}}
                      <td >{{ $show->normal }}</td>
                      {{-- <td >{{ $show->max_normal_place_no }}</td> --}}
                      {{-- <td style="text-align: center;"><input  class="form-check-input"  type="checkbox" name ="upcomming" id ="upcomming" @if($show->status == 'upcomming') selected @endif value="{{$show->status}}"></td> --}}
                      <td>
                        @if (Auth::user()->role == "Multimedia Specialist")
                        {{-- @if (Auth::user()->role == "Admin" && $catagory->role != 'Admin') --}}
                        <a href="#" data-bs-toggle="modal" data-bs-target="#viewcatagory{{$show->id}}" title="Edit Catagory"
                          style="border:none; border-radius:4px; text-decoration: none; height: 25px; margin-right: 8px;"
                          class="get-btn  btn-sm pr-2 pl-2 bx bx-show"></a>

                          <a href="deleteShow/{{$show->id}}" title="Delete Catagory"
                              class="btn btn-danger btn-sm bi bi-trash-fill"
                              style="height: 25px;"
                              onclick="return deleteShow();"></a>
                        @endif

                        </td>
                      </tr>
                  @endif
                  @endforeach
                  @if ($no == 0)
                      <tr style="text-align: center;"><td class="not-found" colspan="7"><h6>No Shows</h6></td></tr>
                  @endif
              </tbody>
              <tbody class="searchData"></tbody>
                  <tfoot>
                      <tr>

                      </tr>
                          <td colspan="4">
                              {{-- {{ $catagorys->onEachSide(1)->links()}} --}}
                        </td>
                      </tr>
                  </tfoot>
              </table>
          </div>

        </div>
      </section>

  </main><!-- End #main -->

{{-- CREATE SHOW --}}
<div style="height: 100%; width: 100%; margin: auto; display: none;"
    class="modal fade" id="addShow" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 50%;">
        <div class="modal-content" style="overflow: auto; height: 90%;">
            <div class="modal-header d-flex" style="height: 40px;">
                <?php $team_logo = ''; ?>

                <div class="modal-title page_title" id="staticBackdropLabel">
                    Add Show
                </div>
                <span style="margin-left: 10px; margin-top: 2px;"> </span>

                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <?php $i=1; ?>
            <div class="modal-body p-3" style=" height: 80%; margin-top: 25px;">
                <div class="pl-5" style="width: 85%; margin: auto;">
                    <form action="/addShow" method="post">
                        @csrf
                    <table class="pl-5" style="width: 100%; margin: auto;">

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
                        <tr style="width: 100%; margin: auto;">
                            <div>
                                <td class="p-2" style="width: 20%;">
                                    <label >Date</label>
                                </td>
                                <td class="p-2" >
                                    <input required class="form-control"  type="date" name ="show_date" id ="show_date" >
                                </td>
                            </div>
                        </tr>
                        <tr style="width: 100%; margin: auto;">
                            <div>
                                <td class="p-2" style="width: 20%;">
                                    <label >Time</label>
                                </td>
                                <td class="p-2" >
                                    <input required class="form-control"  type="time" name ="show_time" id ="show_time" >
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Vip</label>
                                </td>
                                <td class="p-2" >
                                    <input required class="form-control"  type="number" name ="vip" id ="vip" >
                                </td>

                            </div>
                        </tr>
                        {{-- <tr>
                            <div>
                                <td class="p-2">
                                    <label >Vip Chairs</label>
                                </td>
                                <td class="p-2" >
                                    <input required class="form-control"  type="number" name ="max_vip_place_no" id ="max_vip_place_no" >
                                </td>

                            </div>
                        </tr> --}}
                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >normal</label>
                                </td>
                                <td class="p-2" >
                                    <input required class="form-control"  type="number" name ="normal" id ="normal" >
                                </td>

                            </div>
                        </tr>
                        {{-- <tr>
                            <div>
                                <td class="p-2">
                                    <label >Normal Chairs</label>
                                </td>
                                <td class="p-2" >
                                    <input required class="form-control"  type="number" name ="max_normal_place_no" id ="max_normal_place_no" >
                                </td>

                            </div>
                        </tr> --}}
                        {{-- <tr>
                            <div>
                                <td class="p-2">
                                    <label >Up Comming</label>
                            </td>
                                <td class="p-2" >
                                    <input onclick="selectType()" class="form-check-input"  type="checkbox" name ="upcomming" id ="upcomming" >
                                </td>
                            </div>
                        </tr> --}}
                        <tr>
                            <td></td>
                            <td><input  type="text" style="display: none; color: #444; border: none; cursor: none;" id="radio_value" name="radio_value" value=""></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" class="get-btn" value="Add Show">
                            </td>
                        </tr>

                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- /CREATE COUPON--}}

{{-- EDIT USER--}}
@foreach ($shows as $show)
<div style="height: 100%; width: 100%; margin: auto; display: none;"
    class="modal fade" id="viewcatagory{{$show->id}}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 50%;">
        <div class="modal-content" style="overflow: auto; height: 90%;">
            <div class="modal-header d-flex" style="height: 40px;">
                <?php $team_logo = ''; ?>

                <div class="modal-title page_title" id="staticBackdropLabel">
                    {{$show->movie}}
                </div>
                <span style="margin-left: 10px; margin-top: 2px;"> </span>

                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <?php $i=1; ?>
            <div class="modal-body p-3" style=" height: 80%; margin-top: 25px;">
                <form action="/updateShow/{{$show->id}}" method="POST">
                    @csrf
                <table class="pl-5" style="width: 85%; margin: auto;">
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
                    <tr style="width: 100%; margin: auto;">
                        <div>
                            <td class="p-2" style="width: 20%;">
                                <label >Date</label>
                            </td>
                            <td class="p-2" >
                                <?php
                                    $date = new DateTime($show->show_date);
                                    $year = $date -> format('Y');
                                    $month = $date -> format('m');
                                    $day = $date -> format('d');
                                ?>
                                <input required class="form-control"  type="date" name ="show_date" id ="show_date" value="{{$year.'-'.$month.'-'.$day}}">
                            </td>
                        </div>
                    </tr>
                    <tr style="width: 100%; margin: auto;">
                        <div>
                            <td class="p-2" style="width: 20%;">
                                <label >Time</label>
                            </td>
                            <td class="p-2" >
                                <input required class="form-control"  type="time" name ="show_time" id ="show_time" value="{{$show->show_time}}">
                            </td>
                        </div>
                    </tr>
                    <tr style="width: 100%; margin: auto;">
                        <div>
                            <td class="p-2" style="width: 20%;">
                                <label >Vip</label>
                            </td>
                            <td class="p-2" >
                                <input required class="form-control"  type="number" name ="vip" id ="vip" value="{{$show->vip}}">
                            </td>
                        </div>
                    </tr>

                    {{-- <tr>
                        <div>
                            <td class="p-2">
                                <label >Vip Chairs</label>
                        </td>
                            <td class="p-2" >
                                <input required class="form-control"  type="number" name ="max_vip_place_no" id ="max_vip_place_no" value="{{$show->max_vip_place_no}}">
                            </td>
                        </div>
                    </tr> --}}

                    <tr>
                        <div>
                            <td class="p-2">
                                <label >Normal</label>
                        </td>
                            <td class="p-2" >
                                <input required class="form-control"  type="number" name ="normal" id ="normal" value="{{$show->normal}}">
                            </td>
                        </div>
                    </tr>

                    {{-- <tr>
                        <div>
                            <td class="p-2">
                                <label >Normal Chairs</label>
                        </td>
                            <td class="p-2" >
                                <input required class="form-control"  type="number" name ="max_normal_place_no" id ="max_normal_place_no" value="{{$show->max_normal_place_no}}">
                            </td>
                        </div>
                    </tr> --}}

                    {{-- <tr>
                        <div>
                            <td class="p-2">
                                <label >Up Comming</label>
                        </td>
                            <td class="p-2" >
                                <input onclick="selectType1()" class="form-check-input"  type="checkbox" name ="upcomming1" id ="upcomming1" @if($show->status == 'upcomming') checked selected @endif value="{{$show->status}}">
                            </td>
                        </div>
                    </tr> --}}

                    <tr>
                        <td></td>
                        <td><input  type="text" style="display: none; color: #444; border: none; cursor: none;" id="radio_value1" name="radio_value1" value="{{$show->status}}"></td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="submit" class="get-btn" value="Update Show">
                        </td>
                    </tr>
                </table>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@endforeach
{{-- /EDIT USER--}}

@endsection

<script type="text/javascript">
    function deleteShow(){
        if(!confirm("Are You Sure to delete this show?"))
        event.preventDefault();
    }

    function selectType(){
        var rd1 = document.getElementById("upcomming");

        if (rd1.checked == true) {
            document.getElementById('radio_value').value = 'upcomming';
        }
    }

    function selectType1(){
        var rd2 = document.getElementById("upcomming1");

        if (rd2.checked == true) {
            document.getElementById('radio_value1').value = 'upcomming';
        }
    }

    function searchShow(){

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
        var location = "/searchShow/"+input;
        xhttp.open("GET", location, true);
        xhttp.send();
    }
</script>
