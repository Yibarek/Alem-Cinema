@section('header')
<style>
    .notify {
    width: 19px;
    height: 19px;
    color: white;
    font-size: 12px;
    text-align: center;
    font-weight: 600;
    margin-top: 6px;
    margin-left: -2px;
    visibility: hidden;
}
</style>
<div class="d-flex justify-content-between align-items-center" style="width: 97%; margin: auto; margin-top: -11px; margin-bottom: -11px;">
{{-- <div class="container d-flex align-items-center justify-content-lg-between" style=""> --}}

    <h1>
        <a class="logo me-auto me-lg-0" style="color: #fff;" href="{{ url('/') }}">
            <img style="width: 40; height: 40px; margin-right: 5px;" src="../assets/img/logo.png" alt="">
            {{ 'Cinema Rukiya' }}
        </a>
    </h1>
    <!-- Uncomment below if you prefer to use an image logo -->
    <!-- <a href="index.html" class="logo me-auto me-lg-0"><img src="../assets/img/logo.png" alt="" class="img-fluid"></a>-->

    <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
        <li ><a class="nav-link scrollto" id="home" href="/">Home</a></li>

        @auth
            @if (Auth::user()->role == 'Admin')
                <li><a class="nav-link scrollto" id="user" href="/user">Users</a></li>
                <li><a class="nav-link scrollto" id="movie" href="/movie">Movies</a></li>
                <li><a class="nav-link scrollto" id="coupon" href="/coupon">Coupons</a></li>
                <li><a class="nav-link scrollto" id="ticket" href="/ticket">Tickets</a></li>
                <li><a class="nav-link scrollto" id="report" href="/report_request">Report</a></li>
                <li><a class="nav-link scrollto" id="feedback" href="/feedback">Feedbacks <span class="rounded-circle bg-danger" style="min-width: 25px; text-align: center;"  id="Nfeedback" style="color: #fff;"></span></a></li>
                {{-- <script>
                    var xhttp0;
                    xhttp0 = new XMLHttpRequest();
                    xhttp0.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            countFeedback = JSON.parse(this.responseText);
                            if (countFeedback.count > 9) {
                                document.getElementById('Nfeedback').style.visibility = 'visible';
                                document.getElementById('feedback').style.color = '#ffc451';
                                document.getElementById('Nfeedback').innerHTML = '9+';
                            } else if (countFeedback.count > 0) {
                                document.getElementById('Nfeedback').style.visibility = 'visible';
                                document.getElementById('feedback').style.color = '#ffc451';
                                document.getElementById('Nfeedback').innerHTML = countFeedback.count;
                            } else {
                                document.getElementById('feedback').style.color = '#ffdca9';
                                document.getElementById('Nfeedback').style.visibility = 'hidden';
                            }
                        }

                    };
                    var location_unF = "/countFeedback";
                    xhttp0.open("GET", location_unF, true);
                    xhttp0.send();
                </script> --}}
            @endif

            @if (Auth::user()->role == 'Multimedia Specialist')
                <li><a class="nav-link scrollto" id="movie" href="/movie">Movies</a></li>
                <li><a class="nav-link scrollto" id="catagory" href="/catagories">Catagories</a></li>
                <li><a class="nav-link scrollto" id="show" href="/show">Shows</a></li>
                <li><a class="nav-link scrollto" id="ticket" href="/ticket">Tickets</a></li>
                @endif

            @if (Auth::user()->role == 'User')
                <li><a class="nav-link scrollto" id="movie" href="/movie">Movies</a></li>
                <li><a class="nav-link scrollto" id="ticket" href="/userTicket">Tickets</a></li>
                <li><a class="nav-link scrollto" id="account" href="/myaccount">My Account</a></li>
            @endif

            @if (Auth::user()->role == 'Ticket Controller')
                <li><a class="nav-link scrollto" id="movie" href="/movie">Movies</a></li>
                <li><a class="nav-link scrollto" id="ticket" href="/ticket">Tickets</a></li>
                <li><a class="nav-link scrollto" id="today" href="/today">Today</a></li>
            @endif

        @else
            <li><a class="nav-link scrollto" id="about" href="#about">About</a></li>
        @endauth
            @yield('aboutus')
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>

    </nav><!-- .navbar -->



    {{-- <a href="#about" class="get-started-btn scrollto">Get Started</a> --}}
    <ul class="d-flex justify-content-between">
        @guest
            @if (Route::has('register'))
                <li style="list-style: none;">
                    <a class="get-started-btn scrollto" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                </li>
            @endif
            @if (Route::has('login'))
                <li style="list-style: none;">
                    <a class="get-started-btn scrollto" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
            @endif
        @else

        <li class="nav-item dropdown pe-3 d-flex" style="list-style: none;">
            @if (Auth::user()->role == 'admin')
            <label class="d-flex" style="margin-right: -13px; margin-top: 7px;">
                </label>
            @endif

            <a class="nav-link nav-profile d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                <img src="../../image/userProfile/{{Auth::user()->profile}}" width="35" height="35" alt="" class="rounded-circle">
                <span class="d-none d-md-block dropdown-toggle p-2" style="font-weight: 600;  color: #fff">{{Auth::user()->username}}</span>
            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu arrow profile" style="position: absolute; left: -50px">
                <li class="pl-2" style=" text-align: center; height: 36px;  color: #; padding-left: 8px;">
                {{-- <span style="height: 20px; font-weight: 600;  font-size: 16px;">{{Auth::user()->username}}</span><br> --}}

                <span style="font-size: 14px; font-weight: 600;">{{Auth::user()->role}}</span>
                </li>
                <li>

                    <a class="dropdown-item d-flex align-items-center" href="/profile">
                        <span class="bi bi-person"> My Profile</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="/myaccount">
                        <span class="bi bi-credit-card"> My Account </span>
                    </a>
                </li>
                @if (Auth::user()->role != 'Admin')
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#sendFeedback">
                            <span class="bi bi-chat-dots"> Feedback </span>
                        </a>
                    </li>
                @endif
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                        onclick="event.preventDefault();  document.getElementById('logout-form').submit();">

                        <span class="bi bi-box-arrow-left"> {{ __('Logout') }}</span>

                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

        @endguest
        </ul>
</div>
@endsection

{{-- CREATE COUPON--}}
<div style="height: 100%; width: 100%; margin: auto; display: none;"
    class="modal fade" id="sendFeedback" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 45%;">
        <div class="modal-content" style="overflow: auto; height: 60%;">
            <div class="modal-header d-flex" style="height: 40px;">
                <?php $team_logo = ''; ?>

                <div class="modal-title page_title" id="staticBackdropLabel">
                    Send Feedback
                </div>
                <span style="margin-left: 10px; margin-top: 2px;"> </span>

                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <?php $i=1; ?>
            <div class="modal-body p-3" style=" height: 90%; margin-top: 25px;">
                <div class="pl-5" style="width: 85%; margin: auto;">
                    {{-- <form action="/createCoupon" method="post"> --}}
                        {{-- @csrf --}}
                        <div class="row" style=" margin-top: -5px;">

                            <div class="col-lg-12">
                              <form action="/giveFeedback" method="post" >
                                @csrf
                                <div class="form-group mt-3">
                                  <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                                </div>
                                <div class="my-3"></div>
                                <div class="text-center"><input type="submit" class="get-btn" value="Send Message"></div>
                              </form>
                            </div>

                          </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- /SEND COUPON--}}


