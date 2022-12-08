@extends('layouts\app')

<title>Report</title>
@section('content')
<script>document.getElementById('users').style.color = "#ffc451";</script>

<div class="pages p-2">

    <div class="msg" style="position: fixed">
        @if (session('success'))
            <script> document.location.href = "#portfolio"; </script>
            <div class="alert alert-success alert-dismissible fade show msg" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                <i class="msg-c">{{--session('success')--}}</i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('danger'))
        <script> document.location.href = "#testimonials"; </script>
            <div class="alert alert-danger alert-dismissible fade show msg" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{--session('danger')--}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

  <div class="row" style="width: 100%; max-width: 700px;; margin: auto;">
    <div class="col-xxl-12 col-md-12 container3 d-flex p-4 page-header">
      <label class="page_title" style="width: 100%; margin-top: 50px; text-align: center; font-size: 35px; font-weight: 600;"> Cinema Rukiya</label>
    </div>
    <form action="/report">
        <div class="container d-flex p-2 page-header" style=" margin-bottom:50px; margin-left: -43%; width: 100%; min-width: 900px; max-height: 50px;">
            <label class="" style="width: 100%; text-align: center;  font-size: 17px;"><strong>{{--$report--}}</strong></label>
            <label class="" style="width: 90%; margin-right: -60px; text-align: center;  font-size: 17px;">From : </label>
            <input class="form-control" type="date" id="first_date" name="first_date">
            <label class="" style="width: 100%; margin-right: -60px; text-align: center;  font-size: 17px;">To : {{--$to--}}</label>
            <input type="date" class="form-control" style="width: 90%;" id="last_date" name="last_date">
        </div>
        <input type="submit" class="form-control get-btn p-1" style="width: 40%; margin-top:30px; margin: auto;" value="Featch">
    </form>
  </div>

<script>
    function search() {
        var input = document.getElementById("search").value;
        location.replace("/searchUser/"+input);
    }

    function myFunction() {
        if(!confirm("Are You Sure to delete this"))
        event.preventDefault();
    }
   </script>

@endsection
