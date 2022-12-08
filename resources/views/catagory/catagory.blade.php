@extends('layouts.app')
<link href="assets/css/player.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<title>Catagory</title>
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
          <h2 class="d-flex">Coupons
            @if (Auth::user()->role == 'Admin')
                <a title="Add New User" class="ml-2 d-flex" href="#" data-bs-toggle="modal" data-bs-target="#adduser">
                    <i class="" style="color: #fff">A</i>
                    <div style="margin-top: 1px;"><i class="mt-3 rounded-circle get-btn-circle ri ri-add-fill"></i>
                    </div>
                </a>
            @endif
          </h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Catagory</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->


    <section id="portfolio" class="portfolio">
        <div class="container">

          <div class="row se" data-aos="fade-in">
            <div class="col-xxl-4 col-md-4 p-1 search-box" style="margin-top: -50px; max-width: 280px;">
                <input class="ml-3 form-control" id="search" type="text" placeholder="Search User..." name="search movies" value="" style="border: none;">
                <button class="search-btn p-2" onclick="searchQuery()" title="Search" type="submit" style="right: 0; top: 8; position: absolute;"><i class="fa fa-search"></i></button>
            </div>
          </div>

          <div class="" data-aos="fade-up" style="margin-top: 0;">
            <table class="table table-borderless datatable table-hover table-sm all-tables">
                <thead>
                  <tr style="height: 35px; background-color: #fafafa;">
                    <th style="width: 4%" scope="col">#</th>
                    <th style="width: 15%" scope="col">Name</th>
                    <th style="width: 5%" scope="col">Description</th>

                    @if (Auth::user()->role == "Multimedia Specialist" || Auth::user()->role == "Multimedia Specialist")
                      <th style="width: 8%" scope="col">Action</th>
                    @endif

                  </tr>
                </thead>
                <tbody class="allData">

                <?php
                  $no = 0;
                ?>

                  @foreach ($catagorys as $catagory)

                  @if (Auth::user()->role == 'Admin')
                    <tr>
                      <th scope="row">{{++$no}}</th>
                      <td >{{ $catagory->name }}</td>
                      <td>{{ $catagory->description }}</td>
                      <td>
                        @if (Auth::user()->role == "Multimedia Specialist")
                        {{-- @if (Auth::user()->role == "Admin" && $catagory->role != 'Admin') --}}
                        <a href="#" data-bs-toggle="modal" data-bs-target="#viewcatagory{{$catagory->id}}" title="Edit Catagory"
                          style="border:none; border-radius:4px; text-decoration: none; height: 25px; margin-right: 8px;"
                          class="get-btn  btn-sm pr-2 pl-2 bx bx-show"></a>

                          <a href="deleteCatagory/{{$catagory->id}}" title="Delete Coupon"
                              class="btn btn-danger btn-sm bi bi-trash-fill"
                              style="height: 25px;"
                              onclick="return deleteCatagory();"></a>
                        @endif

                        </td>
                      </tr>
                  @endif
                  @endforeach
                  @if ($no == 0)
                      <tr style="text-align: center;"><td class="not-found" colspan="7"><h6>No Catagory</h6></td></tr>
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

{{-- CREATE COUPON--}}
<div style="height: 100%; width: 100%; margin: auto; display: none;"
    class="modal fade" id="adduser" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 35%;">
        <div class="modal-content" style="overflow: auto; height: 50%;">
            <div class="modal-header d-flex" style="height: 40px;">
                <?php $team_logo = ''; ?>

                <div class="modal-title page_title" id="staticBackdropLabel">
                    Add New User
                </div>
                <span style="margin-left: 10px; margin-top: 2px;"> </span>

                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <?php $i=1; ?>
            <div class="modal-body p-3" style=" height: 80%; margin-top: 25px;">
                <div class="pl-5" style="width: 85%; margin: auto;">
                    <form action="/createCoupon" method="post">
                        @csrf
                    <table class="pl-5" style="width: 100%; margin: auto;">

                        <tr style="width: 100%; margin: auto;">
                            <div>
                                <td class="p-2" style="width: 35%;">
                                    <label >Full Name</label>
                                </td>
                                <td class="p-2" style="width: 65%;" >
                                    <select class="selectVal" name="user" id="user" style=" color: #333; width: 100%; height: 40px; border: 1px solid #e1e1e1;">
                                        @foreach ($users as $user)
                                            <option value="{{$user->username}}" style="color: #333;">{{$user->username}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </div>
                        </tr>

                        <tr>
                            <div>
                                <td class="p-2">
                                    <label >Coupon</label>
                            </td>
                                <td class="p-2" >
                                    <input required class="form-control"  type="number" name ="coupon" id ="coupon" >
                                </td>

                            </div>
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" class="get-btn" value="Create Coupon">
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
@foreach ($catagorys as $catagory)
<div style="height: 100%; width: 100%; margin: auto; display: none;"
    class="modal fade" id="viewcoupon{{$catagory->id}}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 35%;">
        <div class="modal-content" style="overflow: auto; height: 50%;">
            <div class="modal-header d-flex" style="height: 40px;">
                <?php $team_logo = ''; ?>

                <div class="modal-title page_title" id="staticBackdropLabel">
                    {{$catagory->user}}
                </div>
                <span style="margin-left: 10px; margin-top: 2px;"> </span>

                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <?php $i=1; ?>
            <div class="modal-body p-3" style=" height: 80%; margin-top: 25px;">
                <form action="/updateCoupon/{{$catagory->id}}" method="POST">
                    @csrf
                <table class="pl-5" style="width: 85%; margin: auto;">
                    <tr style="width: 100%; margin: auto;">
                        <div>
                            <td class="p-2" style="width: 35%;">
                                <label >Full Name</label>
                            </td>
                            <td class="p-2" style="width: 65%;" >
                                <select class="selectVal" name="user" id="user" style=" color: #333; width: 100%; height: 40px; border: 1px solid #e1e1e1;">
                                    @foreach ($users as $user)
                                        <option value="{{$user->username}}" style="color: #333;" @if ($user->role == $catagory->user) selected @endif>{{$user->username}}</option>
                                    @endforeach
                                </select>
                            </td>

                        </div>
                    </tr>

                    <tr>
                        <div>
                            <td class="p-2">
                                <label >Coupon</label>
                        </td>
                            <td class="p-2" >
                                <input required class="form-control"  type="number" name ="coupon" id ="coupon" value="{{$catagory->coupon}}">
                            </td>

                        </div>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="submit" class="get-btn" value="Update Coupon">
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

<script>
    function deleteCoupon(){
        if(!confirm("Are You Sure to delete this coupon?"))
        event.preventDefault();
    }
</script>
