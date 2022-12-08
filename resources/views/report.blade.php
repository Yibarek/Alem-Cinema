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

    <div class="container d-flex p-2 page-header" style="width: 100%; min-width: 700px; margin: auto;">
        <label class="" style="width: 100%; text-align: center;  font-size: 17px;"><strong>Total Ticket Sold </strong></label>
        <label class="" style="width: 90%; margin-right: -40px; text-align: center;  font-size: 17px; font-weight: 600px;">From : </label>
        <?php
            $date = new DateTime($from);
            $year = $date -> format('Y');
            $month = $date -> format('m');
            $day = $date -> format('d');
        ?>
        <input disabled class="form-control" type="date" id="first_date" name="first_date" value="{{$year.'-'.$month.'-'.$day}}">
        <label class="" style="width: 100%; margin-right: -60px; text-align: center;  font-size: 17px; font-weight: 600px;">To : </label>
        <?php
            $date1 = new DateTime($to);
            $year1 = $date1 -> format('Y');
            $month1 = $date1 -> format('m');
            $day1 = $date1 -> format('d');
        ?>
        <input disabled type="date" class="form-control" style="width: 90%;" id="last_date" name="last_date" value="{{$year1.'-'.$month1.'-'.$day1}}">
    </div>
  </div>
    <div style="color: white; height: 10px; margin-right: 100px;">1</div>
    <table class="table table-borderless datatable table-hover table-sm all-tables" style="width: 100%; min-width: 750px; margin: auto;">
        <thead>
            <tr style="height: 35px; background-color: #fafafa;">
                <th style="width: 4%" scope="col">#</th>
                <th style="width: 15%" scope="col">User</th>
                <th style="width: 15%" scope="col">Movie</th>
                <th style="width: 15%" scope="col">Seats</th>
                <th style="width: 15%" scope="col">Amount</th>
              </tr>
        </thead>
        <tbody class="allData">

        <?php
            $no = 0;
        ?>
        <?php $totalSale=0; ?>
        @foreach ($Tickets as $Ticket)

        @if (Auth::user()->role == 'Ticket Controller' || Auth::user()->role == 'Admin' || Auth::user()->username == $Ticket->user)
        <tr>
            <th scope="row"><a href="#" data-bs-toggle="modal" data-bs-target="#ticket{{$Ticket->id}}">{{$Ticket->id}}</a></th>
            <td> {{ $Ticket->user }}</a></td>
            <?php $mov ='';?>

            @foreach ($movies as $movie)
                @if ($movie->id == $Ticket->movie)
                    <?php $mov =$movie->title;?>
                @endif
            @endforeach

            <td >{{ $mov }}</td>
            <td>{{ $Ticket->place_no }}</td>
            <td>{{ $Ticket->amount }}</td>
            <?php $totalSale += $Ticket->amount; ?>

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
        <tfoot>
            <div class="d-flex justify-content-between" style="width: 90%;">
                {{-- <label style="font-size: 18px; font-weight: 600px">TotalUsers: <strong>{{$totalSale}}</strong></label>
                <label style="font-size: 18px; font-weight: 600px">TotalMovies: <strong>{{$totalSale}}</strong></label> --}}
                <label style="font-size: 18px; font-weight: 600px">TotalTickets: <strong>{{$Tcount}}</strong></label>
                <label style="font-size: 18px; font-weight: 600px">TotalAmount: <strong>{{$totalSale}}</strong></label>
            </div>
        </tfoot>
    </table>
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

