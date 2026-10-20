@extends('layouts\app')

<title>Alem Cinema | Sales Report</title>
@section('content')
<script>document.getElementById('users') && (document.getElementById('users').style.color = "#ffc451");</script>

<div class="container py-4" style="min-height: 80vh;">

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
        <div class="col-lg-7 col-md-9">
            <div class="text-center mb-4 mt-2">
                <div class="d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px; border-radius: 16px; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);">
                    <i class="bi bi-bar-chart-line-fill" style="font-size: 28px; color: #f59e0b;"></i>
                </div>
                <h1 class="report-title page_title" style="font-size: 34px; font-weight: 800; margin-bottom: 6px;">
                    <span class="brand-gold" style="color: #f59e0b;">Alem</span> Cinema
                </h1>
                <p class="page-subtitle" style="font-size: 15px; color: var(--ac-text-muted);">Select a date range to generate ticket sales and revenue analytics</p>
            </div>

            <div class="report-card p-4 p-md-5">
                <form action="/report" method="GET">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="first_date" class="form-label fw-bold page-label" style="font-size: 14px;">
                                <i class="bi bi-calendar-event me-1 text-warning"></i> From Date
                            </label>
                            <input class="form-control form-control-lg" type="date" id="first_date" name="first_date" required style="border-radius: 12px; font-size: 15px;">
                        </div>
                        <div class="col-md-6">
                            <label for="last_date" class="form-label fw-bold page-label" style="font-size: 14px;">
                                <i class="bi bi-calendar-check me-1 text-warning"></i> To Date
                            </label>
                            <input type="date" class="form-control form-control-lg" id="last_date" name="last_date" required style="border-radius: 12px; font-size: 15px;">
                        </div>
                    </div>

                    <div class="text-center mt-4 pt-2">
                        <button type="submit" class="get-btn px-5 py-3" style="border-radius: 50px; font-size: 16px; font-weight: 600; border: none; letter-spacing: 0.5px; box-shadow: 0 4px 18px rgba(245, 158, 11, 0.35); cursor: pointer;">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i> Fetch Report
                        </button>
                    </div>
                </form>
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
