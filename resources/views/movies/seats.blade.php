@extends('layouts.app')

<title>Alem Cinema | Reserve Seats</title>

@section('content')
<style>
    .cinema-theatre-box {
        background: radial-gradient(circle at 50% 0%, #171f30 0%, #090c13 85%);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 20px;
        padding: 35px 25px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        max-width: 920px;
        margin: 30px auto 40px auto;
    }

    .screen-glow {
        width: 75%;
        height: 10px;
        margin: 10px auto 35px auto;
        border-radius: 50% 50% 0 0;
        border-top: 3.5px solid #f59e0b;
        position: relative;
        text-align: center;
        /* Subtle, realistic downward glow towards the seats */
        box-shadow: 0 7px 12px -2px rgba(245, 158, 11, 0.35);
        /* Strictly clip anything above the screen line (0px) so NO shadow can appear on the upper side */
        clip-path: inset(0 -40px -40px -40px);
    }

    .screen-glow::after {
        content: "CINEMA SCREEN";
        position: absolute;
        top: 14px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 11px;
        letter-spacing: 5px;
        font-weight: 600;
        color: #64748b;
    }

    .seat-grid-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 25px 0;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .seat-row {
        display: flex;
        justify-content: center;
        margin-bottom: 5px;
    }

    .seat-unit {
        height: 18px;
        width: 24px;
        margin: 2px 3px;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        background-color: #334155;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-block;
    }

    .seat-unit:hover:not(.occupied) {
        transform: scale(1.25);
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.6);
        background-color: #475569;
    }

    .seat-unit.vip {
        background: linear-gradient(135deg, #7c3aed, #4c1d95) !important;
        box-shadow: 0 0 5px rgba(124, 58, 237, 0.4);
    }

    .seat-unit.selected {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
        box-shadow: 0 0 12px rgba(245, 158, 11, 0.9) !important;
        transform: scale(1.18);
    }

    .seat-unit.occupied {
        background-color: #1e293b !important;
        opacity: 0.35;
        cursor: not-allowed !important;
    }

    .aisle-gap-right {
        margin-right: 18px !important;
    }

    .aisle-gap-left {
        margin-left: 18px !important;
    }

    .showcase-pill {
        background: rgba(18, 24, 38, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 30px;
        padding: 8px 24px;
        display: inline-flex;
        align-items: center;
        gap: 20px;
        list-style: none;
        margin: 0 auto 25px auto;
    }

    .showcase-pill li {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #cbd5e1;
        font-size: 13px;
        font-weight: 500;
    }

    .summary-dock {
        background: rgba(18, 24, 38, 0.95);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 16px;
        padding: 20px 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        max-width: 920px;
        margin: 0 auto 50px auto;
    }
</style>

@foreach ($movies as $movie)
<div class="container" style="padding-top: 25px;">
    <form action="/buyTicket/{{$movie->id}}" method="POST">
        @csrf

        <div class="cinema-theatre-box">
            <!-- Header bar with movie title & prices -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3" style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <div>
                    <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 12px; letter-spacing: 1px;">ALEM CINEMA HALL 1</span>
                    <h2 class="text-white mt-2 mb-0" style="font-weight: 800; letter-spacing: 0.5px;">{{$movie->title}}</h2>
                </div>
                <div class="d-flex flex-wrap gap-2 align-items-center mt-2 mt-md-0">
                    <span class="badge px-3 py-2" style="background: rgba(124, 58, 237, 0.2); color: #c084fc; border: 1px solid rgba(124, 58, 237, 0.3); font-size: 13px;">
                        VIP: <strong>{{$movie->vip}}.00 ETB</strong>
                    </span>
                    <span class="badge px-3 py-2" style="background: rgba(51, 65, 85, 0.4); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); font-size: 13px;">
                        Normal: <strong>{{$movie->normal}}.00 ETB</strong>
                    </span>
                    <span class="badge px-3 py-2" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 13px;">
                        <i class="bi bi-wallet2 me-1"></i> Balance: <strong>{{Auth::check() ? Auth::user()->account : 0}}.00 ETB</strong>
                    </span>
                </div>
            </div>

            <!-- Legend -->
            <div class="text-center">
                <ul class="showcase-pill">
                    <li>
                        <div class="seat-unit vip" style="cursor: default; margin: 0;"></div>
                        <span>VIP (Rows 1-2)</span>
                    </li>
                    <li>
                        <div class="seat-unit" style="cursor: default; margin: 0;"></div>
                        <span>Normal</span>
                    </li>
                    <li>
                        <div class="seat-unit selected" style="cursor: default; margin: 0;"></div>
                        <span>Selected</span>
                    </li>
                    <li>
                        <div class="seat-unit occupied" style="cursor: default; margin: 0;"></div>
                        <span>Occupied</span>
                    </li>
                </ul>
            </div>

            <!-- Screen Curve -->
            <div class="screen-glow"></div>

            <!-- Seating Grid -->
            <div class="seat-grid-container">
                @php
                    $z = 1;
                    $k = "0";
                @endphp
                @for ($i = 1; $i <= 16; $i++)
                    <div class="seat-row">
                        @for ($j = 1; $j <= 16; $j++)
                            @php
                                $isOccupied = App\Http\Controllers\ticketController::occupied($movie->id, $z);
                                $isVip = ($i <= 2);
                                $gapRight = ($z==2 || $z==18 || $z==34 || $z==50 || $z==66 || $z==82 || $z==98 || $z==114 || $z==130 || $z==146 || $z==162 || $z==178 || $z==194 || $z==210 || $z==226 || $z==242);
                                $gapLeft = ($z==3 || $z==19 || $z==35 || $z==51 || $z==67 || $z==83 || $z==99 || $z==115 || $z==131 || $z==147 || $z==163 || $z==179 || $z==195 || $z==211 || $z==227 || $z==243 || $z==15 || $z==31 || $z==47 || $z==63 || $z==79 || $z==95 || $z==111 || $z==127 || $z==143 || $z==159 || $z==175 || $z==191 || $z==207 || $z==223 || $z==239 || $z==255);
                            @endphp

                            <div 
                                class="seat-unit @if($isOccupied) occupied @elseif($isVip) vip @endif @if($gapRight) aisle-gap-right @endif @if($gapLeft) aisle-gap-left @endif"
                                id="{{$z}}"
                                title="Seat #{{$z}} @if($isVip)(VIP)@endif"
                                @if(!$isOccupied) onclick="z{{$z}}();" @endif
                            ></div>

                            <input id="in{{$z}}" name="in{{$z}}" style="display: none;" value="x">

                            @if (!$isOccupied)
                            <script>
                                function z{{$z}}() {
                                    var seat{{$z}} = document.getElementById('{{$z}}');
                                    var in{{$z}} = document.getElementById("in{{$z}}");
                                    var selectedElem = document.getElementById("selected");
                                    var costElem = document.getElementById("costs");
                                    var inputSelectedElem = document.getElementById("inputSelected");
                                    var inputCostElem = document.getElementById("inputCosts");
                                    var userBalance = {{Auth::check() ? Auth::user()->account : 0}};

                                    if (in{{$z}}.value == "x") {
                                        var currentSelected = parseInt(selectedElem.innerHTML) || 0;
                                        var currentCost = parseInt(costElem.innerHTML) || 0;
                                        var seatPrice = {{$isVip ? $movie->vip : $movie->normal}};

                                        if ((currentCost + seatPrice) > userBalance) {
                                            alert("Insufficient balance! You have " + userBalance + " ETB in your account. Please redeem a coupon to add funds.");
                                            return;
                                        }

                                        var newTotalSelected = currentSelected + 1;
                                        var newTotalCost = currentCost + seatPrice;

                                        costElem.innerHTML = newTotalCost;
                                        inputCostElem.value = newTotalCost;
                                        in{{$z}}.value = "{{$z}}";
                                        selectedElem.innerHTML = newTotalSelected;
                                        inputSelectedElem.value = newTotalSelected;

                                        @if($isVip)
                                            seat{{$z}}.classList.remove('vip');
                                        @endif
                                        seat{{$z}}.classList.add('selected');
                                    } else {
                                        var currentSelected = parseInt(selectedElem.innerHTML) || 0;
                                        var currentCost = parseInt(costElem.innerHTML) || 0;
                                        var seatPrice = {{$isVip ? $movie->vip : $movie->normal}};

                                        var newTotalSelected = Math.max(0, currentSelected - 1);
                                        var newTotalCost = Math.max(0, currentCost - seatPrice);

                                        costElem.innerHTML = newTotalCost;
                                        inputCostElem.value = newTotalCost;
                                        in{{$z}}.value = "x";
                                        selectedElem.innerHTML = newTotalSelected;
                                        inputSelectedElem.value = newTotalSelected;

                                        seat{{$z}}.classList.remove('selected');
                                        @if($isVip)
                                            seat{{$z}}.classList.add('vip');
                                        @endif
                                    }
                                }
                            </script>
                            @endif

                            @php
                                $z++;
                            @endphp
                        @endfor
                    </div>
                    @if ($i == 2 || $i == 10)
                        <div style="height: 14px;"></div>
                    @endif
                @endfor
            </div>
        </div>

        <!-- Checkout Summary Dock -->
        <div class="summary-dock d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-4 mb-3 mb-md-0">
                <div>
                    <div style="color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Selected Seats</div>
                    <div style="color: #fff; font-size: 24px; font-weight: 800;">
                        <span id="selected" style="color: #f59e0b;">0</span> <span style="font-size: 14px; color: #64748b; font-weight: 500;">seats</span>
                    </div>
                </div>
                <div style="width: 1px; height: 40px; background: rgba(255, 255, 255, 0.1);"></div>
                <div>
                    <div style="color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Total Amount</div>
                    <div style="color: #fff; font-size: 24px; font-weight: 800;">
                        <span id="costs" style="color: #f59e0b;">0</span> <span style="font-size: 14px; color: #94a3b8; font-weight: 500;">ETB</span>
                    </div>
                </div>
            </div>

            <input type="hidden" id="inputSelected" name="inputSelected" value="0">
            <input type="hidden" id="inputCosts" name="inputCosts" value="0">

            <div class="d-flex align-items-center gap-3">
                <a href="/movie" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 50px; font-weight: 600; color: #cbd5e1;">Cancel</a>
                <button type="submit" class="get-btn" style="border-radius: 50px; padding: 12px 36px; font-size: 15px; font-weight: 700; border: none; cursor: pointer;">
                    <i class="bi bi-check2-circle me-1"></i> Confirm & Book Ticket
                </button>
            </div>
        </div>
    </form>
</div>
@endforeach

@endsection
