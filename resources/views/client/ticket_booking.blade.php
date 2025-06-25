@extends('layouts.client.client')

@section('content')
<style>
    .btn-date {
        background-color: #f2f2f2;
        color: #333;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px 12px;
        width: 70px;
        text-align: center;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-date.active,
    .btn-date:focus {
        background-color: #df0e62;
        color: white;
        border-color: #df0e62;
    }

    .btn-date:hover {
        background-color: #e9e9e9;
        color: #000;
    }

    .screen-time.active {
        background-color: #df0e62;
        color: white;
        border-color: #df0e62;
    }
</style>

<div class="container" id="progress-container-id">
    <div class="row">
        <div class="col">
            <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                <div id="form">
                    <ul id="progressbar" class="progressbar-class">
                        <li class="active" id="step1">Chọn xuất chiếu</li>
                        <li id="step2" class="not_active">Chọn ghế</li>
                        <li id="step3" class="not_active">Chọn combo</li>
                        <li id="step4" class="not_active">Thanh toán</li>
                        <li id="step5" class="not_active">E-Ticket</li>
                    </ul>
                    <br>

                    <fieldset>
                        <div id="screen-select-div">
                            <h2>Chọn xuất chiếu</h2>

                            {{-- Danh sách ngày --}}
                            <div class="mb-4 d-flex flex-wrap g-3">
                                @foreach ($days as $day)
                                <button type="button" class="btn btn-date mr-2 mb-2" data-date="{{ $day['date'] }}">
                                    {{ $day['label'] }}
                                </button>

                                @endforeach
                            </div>
                            <ul class="time-ul">
                                @foreach ($groupedShowtimes as $date => $showtimes)
                                <li class="time-li showtime-group" data-date="{{ $date }}" style="display: none;">
                                    @foreach ($showtimes->groupBy(fn($s) => $s->room->name) as $roomName => $roomShowtimes)
                                    <div class="screens">{{ $roomName }}</div>
                                    <div class="time-btn">
                                        @foreach ($roomShowtimes as $showtime)
                                        <button class="screen-time"
                                            data-showtime-id="{{ $showtime->id }}"
                                            data-showtime-date="{{ \Carbon\Carbon::parse($showtime->start_time)->toDateString() }}">
                                            {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                                        </button>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </li>
                                @endforeach
                            </ul>

                        </div>

                        <input type="hidden" id="selected_showtime_id" name="selected_showtime_id" />
                        <input id="screen-next-btn" type="button" name="next-step" class="next-step" value="Continue Booking" disabled />
                    </fieldset>
                    <fieldset>
    <div>
        <iframe id="seat-sel-iframe"
            style="box-shadow: 0 14px 12px 0 var(--theme-border), 0 10px 50px 0 var(--theme-border); width: 800px; height: 550px; display: block; margin-left: auto; margin-right: auto;"
            src="seat_selection/seat_sel.html"></iframe>
    </div>
    <br>
    <input type="button" name="next-step" class="next-step" value="Proceed to Snacks" />
    <input type="button" name="previous-step" class="previous-step" value="Back" />
</fieldset>
<!-- Bước 3: Snack Selection -->
<fieldset>
    <div id="snack-select-div">
        <h2>Snack Selection</h2>
        <div class="snack-container"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px; background: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="snack-item"
                style="display: flex; flex-direction: column; align-items: center; background: white; padding: 15px; border-radius: 8px; text-align: center;">
                <img src="client_assets/assets/images/popcorn_combo.png" alt="Popcorn Combo"
                    style="margin-bottom: 10px; width: 120px; height: 120px; object-fit: cover;" />
                <div class="snack-info">
                    <h4>Popcorn Combo (Large Popcorn + Drink)</h4>
                    <p>Price: $10.00</p>
                </div>
                <div class="snack-quantity" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <button onclick="updateQuantity('popcorn', -1)"
                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">-</button>
                    <input type="number" id="popcorn-quantity" value="0" min="0"
                        style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;"
                        readonly />
                    <button onclick="updateQuantity('popcorn', 1)"
                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">+</button>
                </div>
            </div>
            <div class="snack-item"
                style="display: flex; flex-direction: column; align-items: center; background: white; padding: 15px; border-radius: 8px; text-align: center;">
                <img src="client_assets/assets/images/coke.png" alt="Coke (Medium)"
                    style="margin-bottom: 10px; width: 120px; height: 120px; object-fit: cover;" />
                <div class="snack-info">
                    <h4>Coke (Medium)</h4>
                    <p>Price: $5.00</p>
                </div>
                <div class="snack-quantity" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <button onclick="updateQuantity('coke', -1)"
                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">-</button>
                    <input type="number" id="coke-quantity" value="0" min="0"
                        style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;"
                        readonly />
                    <button onclick="updateQuantity('coke', 1)"
                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">+</button>
                </div>
            </div>
            <div class="snack-item"
                style="display: flex; flex-direction: column; align-items: center; background: white; padding: 15px; border-radius: 8px; text-align: center;">
                <img src="client_assets/assets/images/nachos_combo.jpg" alt="Nachos Combo"
                    style="margin-bottom: 10px; width: 120px; height: 120px; object-fit: cover;" />
                <div class="snack-info">
                    <h4>Nachos Combo (Nachos + Cheese Dip)</h4>
                    <p>Price: $8.00</p>
                </div>
                <div class="snack-quantity" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <button onclick="updateQuantity('nachos', -1)"
                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">-</button>
                    <input type="number" id="nachos-quantity" value="0" min="0"
                        style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;"
                        readonly />
                    <button onclick="updateQuantity('nachos', 1)"
                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">+</button>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <input type="button" name="previous-step" class="previous-step" value="Back"
                style="padding: 10px 20px; background: #e0e0e0; color: #333; border: none; border-radius: 5px;" />
            <input type="button" name="next-step" class="next-step" value="Proceed to Payment"
                style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 5px;" />
        </div>
    </div>
</fieldset>

<!-- Bước 4: Payment -->
<fieldset>
    <div class="payment-container step-content"
        style="display: flex; justify-content: space-between; padding: 20px; gap: 20px; background: #ffffff; border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); max-width: 1200px; margin: 0 auto;">
        <!-- Left Section: Payment Details -->
        <div style="flex: 2; padding: 20px; background: #f9f9f9; border-radius: 8px; border: 1px solid #e9ecef;">
            <h2 style="color: #1a1a1a; font-size: 1.6em; font-weight: 600; margin-bottom: 20px;">Payment Details</h2>

            <!-- Voucher Section -->
            <div style="margin-bottom: 20px;">
                <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 10px;">Voucher</h3>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="text" placeholder="Enter voucher code"
                        style="flex: 1; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 1em;" />
                    <button
                        style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.3s;">Apply</button>
                </div>
            </div>

            <!-- Reward Points Section -->
            <div style="margin-bottom: 20px;">
                <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 10px;">Reward Points</h3>
                <p style="color: #6c757d; margin-bottom: 10px;">Available Points: <span
                        style="color: #ff4b5a; font-weight: 600;">500</span></p>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="number" placeholder="Enter points to use" min="0" max="500"
                        style="flex: 1; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 1em;" />
                    <button
                        style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.3s;">Redeem</button>
                </div>
            </div>

            <!-- Payment Methods Section -->
            <div>
                <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 15px;">Payment Methods</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label
                        style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                        <input type="radio" name="payment-method" style="margin-right: 12px; transform: scale(1.3);" />
                        <span style="flex-grow: 1; color: #1a1a1a;">Credit/Debit Card</span>
                        <span>
                            <i class="fa fa-cc-visa" style="color: #1a1a1a; margin-right: 8px; font-size: 1.2em;"></i>
                            <i class="fa fa-cc-mastercard"
                                style="color: #1a1a1a; margin-right: 8px; font-size: 1.2em;"></i>
                        </span>
                    </label>
                    <label
                        style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                        <input type="radio" name="payment-method" style="margin-right: 12px; transform: scale(1.3);" />
                        <span style="flex-grow: 1; color: #1a1a1a;">PayPal</span>
                        <span>
                            <i class="fa fa-paypal" style="color: #1a1a1a; font-size: 1.2em;"></i>
                        </span>
                    </label>
                    <label
                        style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                        <input type="radio" name="payment-method" style="margin-right: 12px; transform: scale(1.3);" />
                        <span style="flex-grow: 1; color: #1a1a1a;">Google Pay</span>
                        <span>
                            <i class="fab fa-google-pay" style="color: #1a1a1a; font-size: 1.2em;"></i>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Right Section: Summary and Countdown -->
        <div
            style="flex: 1; padding: 20px; background: #f9f9f9; border-radius: 8px; border: 1px solid #e9ecef; text-align: center;">
            <h3 style="color: #1a1a1a; font-size: 1.4em; font-weight: 600; margin-bottom: 20px;">Payment Summary</h3>
            <ul style="list-style: none; padding: 0; margin-bottom: 20px; text-align: left;">
                <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Subtotal: <span
                        style="color: #ff4b5a; font-weight: 600;">$50.00</span></li>
                <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Discount: <span
                        style="color: #28a745; font-weight: 600;">-$5.00</span></li>
                <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Points Used: <span
                        style="color: #28a745; font-weight: 600;">-100 pts</span></li>
                <li
                    style="margin-bottom: 20px; color: #495057; font-size: 1.1em; border-top: 1px solid #e9ecef; padding-top: 12px;">
                    Total: <span style="color: #ff4b5a; font-weight: 600; font-size: 1.2em;">$45.00</span>
                </li>
            </ul>
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                <h2 style="color: #1a1a1a; font-size: 1.2em; margin-bottom: 10px;">Time Remaining</h2>
                <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;">
                    <span
                        style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;">0</span>
                    <span
                        style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;">7</span>
                    <span
                        style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;">47</span>
                </div>
                <p style="color: #6c757d; font-size: 0.9em;">Hours Minutes Seconds</p>
            </div>
        </div>
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <input type="button" name="previous-step" class="previous-step" value="Back"
            style="padding: 12px 30px; background: #6c757d; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;" />
        <input type="button" name="next-step" class="next-step pay-btn" value="Confirm Payment"
            style="padding: 12px 30px; background: #ff4b5a; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;" />
    </div>
</fieldset>

<!-- Bước 5: E-Ticket -->
<fieldset>
    <h2>E-Ticket</h2>
    <div class="ticket-body">
        <div class="ticket">
            <div class="holes-top"></div>
            <div class="title">
                <p class="cinema">MyShowz Entertainment</p>
                <p class="movie-title">Movie Name</p>
            </div>
            <div class="poster">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/25240/only-god-forgives.jpg"
                    alt="Movie: Only God Forgives" />
            </div>
            <div class="info">
                <table class="info-table ticket-table">
                    <tr>
                        <th>SCREEN</th>
                        <th>ROW</th>
                        <th>SEAT</th>
                    </tr>
                    <tr>
                        <td class="bigger">18</td>
                        <td class="bigger">H</td>
                        <td class="bigger">24</td>
                    </tr>
                </table>
                <table class="info-table ticket-table">
                    <tr>
                        <th>PRICE</th>
                        <th>DATE</th>
                        <th>TIME</th>
                    </tr>
                    <tr>
                        <td>RS.12.00</td>
                        <td>4/13/21</td>
                        <td>19:30</td>
                    </tr>
                </table>
            </div>
            <div class="holes-lower"></div>
            <div class="serial">
                <table class="barcode ticket-table">
                    <tr>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                        <td style="background-color:black;"></td>
                        <td style="background-color:white;"></td>
                    </tr>
                </table>
                <table class="numbers ticket-table">
                    <tr>
                        <td>9</td>
                        <td>1</td>
                        <td>7</td>
                        <td>3</td>
                        <td>7</td>
                        <td>5</td>
                        <td>4</td>
                        <td>4</td>
                        <td>4</td>
                        <td>5</td>
                        <td>4</td>
                        <td>1</td>
                        <td>4</td>
                        <td>7</td>
                        <td>8</td>
                        <td>7</td>
                        <td>3</td>
                        <td>4</td>
                        <td>1</td>
                        <td>4</td>
                        <td>5</td>
                        <td>2</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <input type="button" name="previous-step" class="previous-step home-page-btn" value="Browse to Home Page"
            style="padding: 12px 30px; background: #6c757d; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;"
            onclick="location.href='index.html';" />
    </div>
</fieldset>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log("✅ Script chọn suất chiếu đã chạy");

    document.addEventListener('DOMContentLoaded', function() {
        let currentStep = 1;

        const dateButtons = document.querySelectorAll('.btn-date');
        const showtimeGroups = document.querySelectorAll('.showtime-group');
        const showtimeButtons = document.querySelectorAll('.screen-time');
        const nextBtn = document.getElementById('screen-next-btn');
        const selectedShowtimeInput = document.getElementById('selected_showtime_id');

        console.log("=== Danh sách nút ngày ===");
        dateButtons.forEach(btn => console.log("btn-date:", btn.dataset.date));

        console.log("=== Danh sách group suất chiếu ===");
        showtimeGroups.forEach(g => console.log("showtime-group:", g.dataset.date));

        // Chọn ngày
        dateButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const selectedDate = this.dataset.date;
                console.log("👉 Ngày được chọn:", selectedDate);

                // Active trạng thái
                dateButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Ẩn/hiện các suất chiếu theo ngày
                showtimeGroups.forEach(group => {
                    const isMatch = group.dataset.date === selectedDate;
                    console.log(`⏱️ So sánh: group=${group.dataset.date} vs selected=${selectedDate} => ${isMatch}`);
                    group.style.display = isMatch ? 'block' : 'none';
                });

                // Reset chọn suất chiếu
                showtimeButtons.forEach(b => b.classList.remove('active'));
                nextBtn.disabled = true;
                selectedShowtimeInput.value = '';
            });
        });

        // Chọn suất chiếu
        showtimeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                showtimeButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const showtimeId = this.dataset.showtimeId;
                selectedShowtimeInput.value = showtimeId;
                nextBtn.disabled = false;
                console.log('🎟️ Suất chiếu đã chọn:', showtimeId);
            });
        });

        // Bước tiếp theo
        nextBtn.addEventListener('click', function() {
            if (currentStep < 5) {
                currentStep++;
                showStep(currentStep);
            }
        });

        function showStep(step) {
            document.querySelectorAll('fieldset').forEach((fs, i) => {
                fs.style.display = (i === step - 1) ? 'block' : 'none';
            });
            document.querySelectorAll('#progressbar li').forEach((li, i) => {
                li.classList.toggle('active', i < step);
            });
        }

        // Khởi tạo
        if (dateButtons.length > 0) {
            console.log("⚡ Auto chọn ngày đầu tiên:", dateButtons[0].dataset.date);
            dateButtons[0].click();
        }
        showStep(currentStep);
    });
</script>
@endsection