@extends('layouts\app')

<title>Alem Cinema | Sales Report</title>
@section('content')
<script>document.getElementById('users') && (document.getElementById('users').style.color = "#ffc451");</script>

<div class="container py-4" style="min-height: 85vh;">

    <div class="msg" style="position: fixed; z-index: 999; top: 80px; right: 20px;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                <span>{{session('success')}}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                <span>{{session('danger')}}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="text-center mb-4 mt-2">
                <div class="d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px; border-radius: 16px; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);">
                    <i class="bi bi-receipt-cutoff" style="font-size: 28px; color: #f59e0b;"></i>
                </div>
                <h1 class="report-title page_title" style="font-size: 34px; font-weight: 800; margin-bottom: 6px;">
                    <span class="brand-gold" style="color: #f59e0b;">Alem</span> Cinema
                </h1>
                <p class="page-subtitle" style="font-size: 15px; color: var(--ac-text-muted);">Ticket Sales & Revenue Report</p>
            </div>

            <!-- Date Range & Summary Card -->
            <?php
                $date = new DateTime($from);
                $year = $date -> format('Y');
                $month = $date -> format('m');
                $day = $date -> format('d');

                $date1 = new DateTime($to);
                $year1 = $date1 -> format('Y');
                $month1 = $date1 -> format('m');
                $day1 = $date1 -> format('d');
            ?>
            <div class="report-card p-3 p-md-4 mb-4">
                <div class="row align-items-center g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold" style="color: var(--ac-text-main); font-size: 14px;">
                                <i class="bi bi-calendar-range me-1 text-warning"></i> Report Period:
                            </span>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 8px;">
                                {{$year.'-'.$month.'-'.$day}} &nbsp;—&nbsp; {{$year1.'-'.$month1.'-'.$day1}}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-md-end gap-3 align-items-center flex-wrap">
                        <div class="d-flex align-items-center gap-2 px-3 py-1 rounded-3" style="background: rgba(245, 158, 11, 0.08); border: 1px solid var(--ac-surface-border-gold);">
                            <i class="bi bi-ticket-perforated-fill text-warning" style="font-size: 20px;"></i>
                            <div>
                                <small style="color: var(--ac-text-muted); display: block; font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">TOTAL TICKETS</small>
                                <strong style="color: var(--ac-text-main); font-size: 15px;">{{$Tcount}}</strong>
                            </div>
                        </div>
                        <a href="/report_request" class="btn btn-outline-warning btn-sm d-inline-flex align-items-center" style="border-radius: 8px; font-weight: 600; padding: 6px 14px;">
                            <i class="bi bi-arrow-left me-1"></i> New Query
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive report-card p-0 overflow-hidden" style="border-radius: 14px;">
                <table class="table table-borderless datatable table-hover table-sm all-tables mb-0" style="width: 100%;">
                    <thead>
                        <tr style="height: 42px;">
                            <th style="width: 8%; padding-left: 18px;" scope="col">#</th>
                            <th style="width: 22%" scope="col"><i class="bi bi-person me-1"></i> User</th>
                            <th style="width: 32%" scope="col"><i class="bi bi-film me-1"></i> Movie</th>
                            <th style="width: 18%" scope="col"><i class="bi bi-grid me-1"></i> Seats</th>
                            <th style="width: 20%; text-align: right; padding-right: 20px;" scope="col"><i class="bi bi-cash me-1"></i> Amount (ETB)</th>
                        </tr>
                    </thead>
                    <tbody class="allData">
                    <?php
                        $no = 0;
                        $totalSale = 0;
                    ?>
                    @foreach ($Tickets as $Ticket)
                        @if (Auth::check() && (Auth::user()->role == 'Ticket Controller' || Auth::user()->role == 'Admin' || Auth::user()->username == $Ticket->user))
                        <tr style="border-bottom: 1px solid var(--ac-surface-border);">
                            <th style="padding-left: 18px;" scope="row">
                                <span class="badge" style="background: rgba(100, 116, 139, 0.2); color: var(--ac-text-main); font-weight: 600;">#{{$Ticket->id}}</span>
                            </th>
                            <td class="fw-semibold">{{ $Ticket->user }}</td>
                            <?php $mov ='';?>
                            @foreach ($movies as $movie)
                                @if ($movie->id == $Ticket->movie)
                                    <?php $mov =$movie->title;?>
                                @endif
                            @endforeach
                            <td><span class="text-warning fw-bold">{{ $mov }}</span></td>
                            <td><span class="badge" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25);">{{ $Ticket->place_no }}</span></td>
                            <td style="text-align: right; padding-right: 20px;" class="fw-bold text-success">{{ number_format($Ticket->amount) }} ETB</td>
                            <?php $totalSale += $Ticket->amount; ?>
                            @php
                                $no++;
                            @endphp
                        </tr>
                        @endif
                    @endforeach
                    @if ($no == 0)
                        <tr style="text-align: center;"><td class="not-found py-5 text-muted" colspan="5"><h6><i class="bi bi-inbox me-2"></i>No Tickets Found for This Date Range</h6></td></tr>
                    @endif
                    </tbody>
                    <tfoot>
                        <tr style="background: rgba(245, 158, 11, 0.08); border-top: 2px solid var(--ac-primary);">
                            <td colspan="3" style="padding: 16px 18px; font-weight: 700; color: var(--ac-text-main); font-size: 15px;">
                                Total Revenue
                            </td>
                            <td style="padding: 16px; font-weight: 600; color: var(--ac-text-main);">
                                <span class="badge bg-warning text-dark fw-bold">{{$no}} Transactions</span>
                            </td>
                            <td style="text-align: right; padding: 16px 20px 16px 0; font-size: 18px; font-weight: 800; color: #10b981;">
                                {{ number_format($totalSale) }} ETB
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
    function search() {
        var input = document.getElementById("search").value;
        location.replace("/searchUser/"+input);
    }
</script>

@endsection
