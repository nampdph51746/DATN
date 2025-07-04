<style>
.seat-selection-wrapper {
    padding: 20px;
    background: #0d0d0d;
    max-width: 1200px;
    margin: auto;
    font-family: 'Roboto', sans-serif;
    color: #ffffff;
}

.seat-selection-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.section-title {
    font-size: 1.8em;
    text-align: center;
    color: #ffffff;
    margin-bottom: 20px;
    font-weight: 600;
    padding-bottom: 5px;
    border-bottom: 2px solid #e5006e;
}

.screen {
    text-align: center;
    background: linear-gradient(to right, #e5006e, #1a1a1a);
    color: #ffffff;
    padding: 12px;
    font-weight: 600;
    border-radius: 8px;
    margin-bottom: 16px;
    box-shadow: 0 4px 8px rgba(229, 0, 110, 0.3);
    border: 2px solid #ffffff;
}

.seat-map {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.seat-row-container {
    display: flex;
    align-items: center;
}

.row-label {
    width: 28px;
    text-align: center;
    font-weight: 600;
    color: #ffffff;
    margin-right: 10px;
    background-color: #333333;
    border-radius: 4px;
    padding: 2px 0;
    border: 1px solid #d3d3d3;
}

.seat-row {
    display: grid;
    gap: 6px;
}

.seat {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    text-align: center;
    line-height: 40px;
    transition: all 0.2s ease;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    background-color: #28a745;
    color: #ffffff;
    border: 2px solid #ffffff;
}

.seat:hover {
    transform: scale(1.1);
}

.seat.reserved {
    cursor: not-allowed;
    opacity: 0.6;
    background-color: #dc3545 !important;
}

.seat.selected {
    background-color: #e5006e;
    color: #ffffff;
    border: 3px solid #ffffff;
    box-shadow: 0 0 10px rgba(229, 0, 110, 0.5);
}

.seat.empty {
    background-color: #222222;
    cursor: default;
    border: 1px dashed #444444;
}

.seat-legend {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.legend-item {
    display: flex;
    align-items: center;
    font-size: 0.95em;
    color: #d3d3d3;
}

.legend-color {
    display: inline-block;
    width: 18px;
    height: 18px;
    margin-right: 6px;
    border-radius: 4px;
    border: 1px solid #ffffff;
}

.legend-color.selected {
    background-color: #e5006e;
}

.legend-color.reserved {
    background-color: #dc3545;
}

.summary-section {
    background: #1c1c1c;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    border: 2px solid #333333;
}

.summary-title {
    font-size: 1.4em;
    margin-bottom: 15px;
    color: #ffffff;
    text-align: center;
    font-weight: 600;
    padding-bottom: 5px;
    border-bottom: 2px solid #e5006e;
}

.ticket-info {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.movie-poster {
    flex: 0 0 120px;
}

.poster-image {
    width: 120px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    object-fit: cover;
}

.info-details {
    flex: 1;
}

.info-line {
    margin-bottom: 12px;
    font-size: 0.75em;
    display: flex;
    justify-content: space-between;
    color: #d3d3d3;
}

.info-line.total {
    font-weight: 600;
    font-size: 1.05em;
    border-top: 1px solid #444444;
    padding-top: 10px;
    margin-top: 15px;
    color: #ffffff;
}

.timer-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #333333;
}

.snack-table {
    width: 100%;
    margin-top: 10px;
    border-collapse: collapse;
}

.snack-table th, .snack-table td {
    padding: 8px;
    text-align: left;
    color: #d3d3d3;
    border-bottom: 1px solid #444444;
}

.snack-table th {
    font-weight: 600;
}

.timer-box {
    background: #e5006e;
    color: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 1.4em;
    font-weight: bold;
    min-width: 50px;
    display: inline-block;
    text-align: center;
}

.timer-label {
    color: #d3d3d3;
    font-size: 0.85em;
    margin-top: 4px;
}

@media (max-width: 768px) {
    .seat-selection-grid {
        grid-template-columns: 1fr;
    }

    .seat {
        width: 30px;
        height: 30px;
        line-height: 30px;
        font-size: 10px;
    }

    .ticket-info {
        flex-direction: column;
        gap: 15px;
    }

    .movie-poster {
        flex: 0 0 100px;
    }

    .poster-image {
        width: 100px;
    }
}

/* Custom classes */
.bg-dark { background-color: #1c1c1c; }
.bg-dark-gray { background-color: #222222; }
.bg-fuchsia { background-color: #e5006e; }
.bg-gradient-fuchsia {
    background: linear-gradient(to right, #e5006e, #1a1a1a);
}

.custom-btn {
    padding: 10px 24px;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    border: none;
    border-radius: 9999px; /* Full rounded */
    transition: background-color 0.3s ease;
}

.bg-danger { background-color: #dc3545; }
.text-white { color: #ffffff; }
.text-gray-light { color: #d3d3d3; }
.border-white { border-color: #ffffff; }
.border-fuchsia { border-color: #e5006e; }
.border-light { border-color: #d3d3d3; }
.form-select { appearance: none; }
.shadow-lg { box-shadow: 0 4px 12px rgba(0,0,0,0.5); }
</style>

@extends('layouts.client.client')
@section('content')
    <div class="container" id="progress-container-id">
        <div class="row">
            <div class="col">
                <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                    <div id="form">
                        <ul id="progressbar" class="progressbar-class">
                            <li class="active" id="step1">Show timing selection</li>
                            <li id="step2" class="not_active">Seat Selection</li>
                            <li id="step3" class="not_active">Snack Selection</li>
                            <li id="step4" class="not_active">Payment</li>
                            <li id="step5" class="not_active">E-Ticket</li>
                        </ul>
                        <br>
                        <fieldset>
                            <div id="screen-select-div">
                                <!-- <h2>Show time Selection</h2> -->
                                <h3>{{ $movie->title }}</h3>
                                @if (empty($dates))
                                    <p>Không có suất chiếu nào cho phim này.</p>
                                @else
                                    <div class="carousel carousel-nav"
                                        data-flickity='{"contain": true, "pageDots": false }'>
                                        @foreach ($dates as $index => $date)
                                            <div class="carousel-cell" id="{{ $index + 1 }}"
                                                onclick="myFunction({{ $index + 1 }}, '{{ $date['full_date'] }}')">
                                                <div class="date-numeric">{{ $date['date'] }}</div>
                                                <div class="date-day">{{ $date['day'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <ul class="time-ul" id="time-ul">
                                        <!-- Danh sách phòng chiếu và thời gian sẽ được cập nhật động qua JavaScript -->
                                    </ul>
                                @endif
                            </div>
                            <input id="screen-next-btn" type="button" name="next-step" class="next-step"
                                value="Continue Booking" disabled />
                        </fieldset>
                        <fieldset>
                            <div>
                                <!-- <h2>Seat Selection</h2> -->
                                <iframe id="seat-map-iframe" src="" width="100%" height="700"
                                    style="border: none; overflow: hidden; display: none;" onload="on onIframeLoad()">
                                </iframe>
                                <div id="seat-map-placeholder" style="display: block;">
                                    <p>Please select a showtime to view the seat map.</p>
                                </div>
                            </div>
                            <br>
                            <input type="button" name="next-step" id="proceed-snack-btn" class="next-step"
                                value="Proceed to Snacks" />
                            <input type="button" name="previous-step" id="back-btn" class="previous-step"
                                value="Back" />
                        </fieldset>
                        <!-- Bước 3: Snack Selection -->
                        <fieldset>
                            <div id="snack-select-div" class="flex gap-4 p-4">
                                <!-- Left side: Snack Cards -->
                                <div class="basis-[70%] bg-[#121212] rounded-lg shadow-md p-4">
                                    <h2 class="text-lg text-white font-semibold mb-3 border-b border-fuchsia pb-1">Snack Selection</h2>
                                    <div class="grid grid-cols-[repeat(auto-fit,minmax(220px,1fr))] gap-4">
                                        @foreach ($products as $product)
                                            <div class="flex flex-col items-center bg-white p-3 rounded-lg text-center">
                                                <img src="{{ asset('storage/' . $product->image_url) }}"
                                                    alt="{{ $product->name }}"
                                                    class="mb-2 w-24 h-24 object-cover rounded" />
                                                <div class="snack-info">
                                                    <h4 class="text-base">{{ $product->name }}</h4>
                                                    @if ($product->productVariants->isNotEmpty())
                                                        <select class="variant-select form-select mt-2 p-1.5 rounded-md border text-sm"
                                                                data-product-id="{{ $product->id }}"
                                                                onchange="updateVariant('{{ $product->id }}')">
                                                            @foreach ($product->productVariants as $variant)
                                                                <option value="{{ $variant->id }}"
                                                                        data-price="{{ $variant->price }}"
                                                                        data-sku="{{ $variant->sku }}">
                                                                    {{ $variant->productVariantOptions->map(fn($option) => $option->attributeValue->value)->join(' - ') }}
                                                                    ({{ number_format($variant->price) }} VNĐ)
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <p class="text-sm">No variants available</p>
                                                    @endif
                                                </div>
                                                <div class="snack-quantity flex items-center gap-2 mt-2"
                                                    data-product-id="{{ $product->id }}">
                                                    <button onclick="updateQuantity('{{ $product->id }}', -1)"
                                                            class="p-1.5 bg-red-500 text-white border-none rounded-md text-sm">-</button>
                                                    <input type="number" id="quantity-{{ $product->id }}" value="0" min="0"
                                                        class="w-12 text-center border border-gray-300 rounded-md p-1 text-sm"
                                                        readonly />
                                                    <button onclick="updateQuantity('{{ $product->id }}', 1)"
                                                            class="p-1.5 bg-red-500 text-white border-none rounded-md text-sm">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Right side: Thông tin đặt vé -->
                                <div class="basis-[30%] summary-section">
                                    <h3 class="summary-title">Thông tin đặt vé</h3>
                                    <div class="ticket-info">
                                        <div class="movie-poster">
                                            <img src="{{ $movie->image_path ? asset('storage/' . $movie->image_path) : asset('images/default-poster.jpg') }}"
                                                alt="{{ $movie->title ?? 'Chưa xác định' }}"
                                                class="poster-image" />
                                        </div>
                                        <div class="info-details">
                                            <div class="info-line"><strong>Phim:</strong> <span id="movie-title">{{ $movie->title ?? 'Chưa xác định' }}</span></div>
                                            <div class="info-line"><strong>Rạp:</strong> <span id="summary-cinema-name">{{ $cinema->name ?? 'Chưa xác định' }}</span></div>
                                            <div class="info-line"><strong>Suất:</strong> <span id="selected-showtime">{{ $showtime?->start_time?->format('H:i d/m/Y') ?? 'Chưa xác định' }}</span></div>
                                            <div class="info-line"><strong>Phòng:</strong> <span id="room-name">{{ $room->name ?? 'Chưa xác định' }}</span></div>
                                        </div>
                                    </div>

                                    <div class="info-line"><strong>Ghế:</strong> <span id="selected-seats">{{ $selectedSeats ?? 'Chưa chọn ghế' }}</span></div>
                                    <div class="info-line"><strong>Tiền vé:</strong> <span id="ticket-price">{{ number_format($totalPrice ?? 0) }} ₫</span></div>

                                    <hr style="border: none; height: 2px; margin: 8px 0;">
                                    <!-- Danh sách đồ ăn -->
                                    <table id="summary-table-body" class="snack-table text-sm mt-2">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width: 50%;">Tên món</th>
                                                <th style="width: 25%;">Số lượng</th>
                                                <th style="width: 25%;">Giá</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div class="info-line mt-1"><strong>Tổng tiền đồ ăn:</strong> <span id="snack-total">0 ₫</span></div>

                                    <!-- Dòng hr màu hồng -->
                                    <hr style="border: none; height: 2px; background-color: #e5006e; margin: 8px 0;">

                                    <!-- Tổng cộng -->
                                    <div class="info-line"><strong>Tổng cộng:</strong> <span id="summary-total">0 ₫</span></div>

                                    <!-- Đếm ngược -->
                                    <div class="timer-section mt-4" id="timer-section">
                                        <h2 style="color: #ffffff; font-size: 1.2em; margin-bottom: 10px;"><strong>Time Remaining</strong></h2>
                                        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;" id="timer-display">
                                            <div style="text-align: center;">
                                                <span id="hours-snack" class="timer-box">00</span>
                                                <div class="timer-label">Giờ</div>
                                            </div>
                                            <div style="text-align: center;">
                                                <span id="minutes-snack" class="timer-box">00</span>
                                                <div class="timer-label">Phút</div>
                                            </div>
                                            <div style="text-align: center;">
                                                <span id="seconds-snack" class="timer-box">00</span>
                                                <div class="timer-label">Giây</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between mt-4 px-4">
                                <input type="button" name="previous-step" id="back-btn"
                                    class="previous-step custom-btn bg-gray-600 hover:bg-gray-700"
                                    value="Back" />
                                <input type="button" name="next-step" id="proceed-snack-btn"
                                    class="next-step custom-btn bg-[#e5006e] hover:bg-[#c4005c]"
                                    value="Proceed to Payment" />
                            </div>
                        </fieldset>
                        <!-- Bước 4: Payment -->
                        <fieldset>
                            <div class="payment-container step-content"
                                style="display: flex; justify-content: space-between; padding: 20px; gap: 20px; background: #ffffff; border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); max-width: 1200px; margin: 0 auto;">
                                <!-- Left Section: Payment Details -->
                                <div
                                    style="flex: 2; padding: 20px; background: #f9f9f9; border-radius: 8px; border: 1px solid #e9ecef;">
                                    <h2 style="color: #1a1a1a; font-size: 1.6em; font-weight: 600; margin-bottom: 20px;">
                                        Payment Details</h2>
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
                                        <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 10px;">Reward Points
                                        </h3>
                                        <p style="color: #6c757d; margin-bottom: 10px;">Available Points: <span
                                                style="color: #ff4b5a; font-weight: 600;">500</span></p>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="number" placeholder="Enter points to use" min="0"
                                                max="500"
                                                style="flex: 1; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 1em;" />
                                            <button
                                                style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.3s;">Redeem</button>
                                        </div>
                                    </div>
                                    <!-- Payment Methods Section -->
                                    <div>
                                        <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 15px;">Payment Methods
                                        </h3>
                                        <div style="display: flex; flex-direction: column; gap: 12px;">
                                            <label
                                                style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                                                <input type="radio" name="payment-method"
                                                    style="margin-right: 12px; transform: scale(1.3);" />
                                                <span style="flex-grow: 1; color: #1a1a1a;">Credit/Debit Card</span>
                                                <span>
                                                    <i class="fa fa-cc-visa"
                                                        style="color: #1a1a1a; margin-right: 8px; font-size: 1.2em;"></i>
                                                    <i class="fa fa-cc-mastercard"
                                                        style="color: #1a1a1a; margin-right: 8px; font-size: 1.2em;"></i>
                                                </span>
                                            </label>
                                            <label
                                                style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                                                <input type="radio" name="payment-method"
                                                    style="margin-right: 12px; transform: scale(1.3);" />
                                                <span style="flex-grow: 1; color: #1a1a1a;">PayPal</span>
                                                <span>
                                                    <i class="fa fa-paypal" style="color: #1a1a1a; font-size: 1.2em;"></i>
                                                </span>
                                            </label>
                                            <label
                                                style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                                                <input type="radio" name="payment-method"
                                                    style="margin-right: 12px; transform: scale(1.3);" />
                                                <span style="flex-grow: 1; color: #1a1a1a;">Google Pay</span>
                                                <span>
                                                    <i class="fab fa-google-pay"
                                                        style="color: #1a1a1a; font-size: 1.2em;"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Right Section: Summary and Countdown -->
                                <div
                                    style="flex: 1; padding: 20px; background: #f9f9f9; border-radius: 8px; border: 1px solid #e9ecef; text-align: center;">
                                    <h3 style="color: #1a1a1a; font-size: 1.4em; font-weight: 600; margin-bottom: 20px;">
                                        Payment Summary</h3>
                                    <ul style="list-style: none; padding: 0; margin-bottom: 20px; text-align: left;">
                                        <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Subtotal: <span
                                                style="color: #ff4b5a; font-weight: 600;" id="subtotal-display">0 ₫</span>
                                        </li>
                                        <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Discount: <span
                                                style="color: #28a745; font-weight: 600;" id="discount-display">0 ₫</span>
                                        </li>
                                        <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Points Used:
                                            <span style="color: #28a745; font-weight: 600;">0 pts</span>
                                        </li>
                                        <li
                                            style="margin-bottom: 20px; color: #495057; font-size: 1.1em; border-top: 1px solid #e9ecef; padding-top: 12px;">
                                            Total: <span style="color: #ff4b5a; font-weight: 600; font-size: 1.2em;"
                                                id="total-display">0 ₫</span>
                                        </li>
                                    </ul>
                                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                                        <h2 style="color: #1a1a1a; font-size: 1.2em; margin-bottom: 10px;">Time Remaining
                                        </h2>
                                        <div
                                            style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;">
                                            <span
                                                style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;"
                                                id="hours-payment">00</span>
                                            <span
                                                style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;"
                                                id="minutes-payment">00</span>
                                            <span
                                                style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;"
                                                id="seconds-payment">00</span>
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
                                        <p class="movie-title" id="ticket-movie-title">{{ $movie->title }}</p>
                                    </div>
                                    <div class="poster">
                                        <img src="{{ \Storage::url($movie->image_path ?? '/images/default-poster.jpg') }}"
                                            alt="{{ $movie->title }}" />
                                    </div>
                                    <div class="info">
                                        <table class="info-table ticket-table">
                                            <tr>
                                                <th>SCREEN</th>
                                                <th>ROW</th>
                                                <th>SEAT</th>
                                            </tr>
                                            <tr>
                                                <td class="bigger" id="ticket-screen-display">N/A</td>
                                                <td class="bigger" id="ticket-row-display">N/A</td>
                                                <td class="bigger" id="ticket-seat-display">N/A</td>
                                            </tr>
                                        </table>
                                        <table class="info-table ticket-table">
                                            <tr>
                                                <th>PRICE</th>
                                                <th>DATE</th>
                                                <th>TIME</th>
                                            </tr>
                                            <tr>
                                                <td id="ticket-price-final">0 ₫</td>
                                                <td id="ticket-date-display">N/A</td>
                                                <td id="ticket-time-display">N/A</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="holes-lower"></div>
                                    <div class="serial">
                                        <table class="barcode ticket-table">
                                            <tr>
                                                <td style="background-color:black;"></td>
                                                <!-- (Barcode cells unchanged for brevity) -->
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
                                <input type="button" name="previous-step" class="previous-step home-page-btn"
                                    value="Browse to Home Page"
                                    style="padding: 12px 30px; background: #6c757d; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;"
                                    onclick="location.href='index.html';" />
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden elements for updateOrderSummary -->
        <div style="display: none;">
            <div id="ticket-count">0</div>
            <div id="ticket-price">0 ₫</div>
            <div id="selected-seats">N/A</div>
            <div id="selected-showtime">N/A</div>
            <div id="discount-amount">0 ₫</div>
            <div id="order-total">0 ₫</div>
            <div id="total-amount">0 ₫</div>
            <div id="ticket-screen">N/A</div>
            <div id="ticket-row">N/A</div>
            <div id="ticket-seat">N/A</div>
            <div id="ticket-price-display">0 ₫</div>
            <div id="ticket-date">N/A</div>
            <div id="ticket-time">N/A</div>
            <div id="movie-title">{{ $movie->name ?? 'N/A' }}</div>
            <div id="cinema-name">N/A</div>
            <div id="room-name">N/A</div>
        </div>
    </div>
@endsection

<script>
    let currentStep = 1;
    let prevId = "1";
    let selectedTime = null;
    let selectedShowtimeId = null;
    let selectedDate = @json($dates[0]['full_date'] ?? '');
    let selectedSeats = [];
    let ticketPrice = 0;
    let snackTotal = 0;
    let discount = 0;
    let cinemaName = 'N/A'; // Thêm biến toàn cục cho cinemaName
    const showtimesData = @json($showtimesData);
    const roomsData = @json($roomsData);
    const movieTitle = @json($movie->name ?? 'N/A');

    // Lưu trữ thông tin biến thể theo productId
    let variantData = {};

    let countdownInterval = null;
    let countdownEndTime = null;

    function startCountdown(durationInSeconds) {
        const endTime = new Date().getTime() + durationInSeconds * 1000;
        countdownEndTime = endTime;

        updateTimerDisplay(); // Chạy ngay lập tức
        if (countdownInterval) clearInterval(countdownInterval);
        countdownInterval = setInterval(updateTimerDisplay, 1000);
    }

    function updateTimerDisplay() {
        if (!countdownEndTime) return;
        const now = new Date().getTime();
        let distance = countdownEndTime - now;

        if (distance <= 0) {
            clearInterval(countdownInterval);
            countdownInterval = null;
            ['hours-snack', 'minutes-snack', 'seconds-snack', 'hours-payment', 'minutes-payment', 'seconds-payment'].forEach(id => {
                const element = document.getElementById(id);
                if (element) element.textContent = "00";
            });
            return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        ['hours-snack', 'hours-payment'].forEach(id => {
            const element = document.getElementById(id);
            if (element) element.textContent = String(hours).padStart(2, '0');
        });
        ['minutes-snack', 'minutes-payment'].forEach(id => {
            const element = document.getElementById(id);
            if (element) element.textContent = String(minutes).padStart(2, '0');
        });
        ['seconds-snack', 'seconds-payment'].forEach(id => {
            const element = document.getElementById(id);
            if (element) element.textContent = String(seconds).padStart(2, '0');
        });
    }

    window.onload = function() {
        showStep(currentStep);
        document.getElementById("screen-next-btn").disabled = true;
        updateShowtimes(selectedDate);
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', handleNextStep);
        });
        document.querySelectorAll('.previous-step').forEach(button => {
            button.addEventListener('click', handlePreviousStep);
        });

        // Khởi tạo variant data
        document.querySelectorAll('.variant-select').forEach(select => {
            const productId = select.getAttribute('data-product-id');
            const selectedOption = select.options[select.selectedIndex];
            variantData[productId] = {
                variantName: selectedOption.text.split(' (')[0],
                price: parseFloat(selectedOption.getAttribute('data-price')) || 0
            };
            select.addEventListener('change', () => updateVariant(productId));
        });

        // Update summary table on page load
        updateOrderSummary();
    };

    function onIframeLoad() {
        console.log('Iframe loaded');
        const iframe = document.getElementById('seat-map-iframe');
        if (iframe && iframe.contentWindow && selectedShowtimeId) {
            iframe.contentWindow.postMessage({
                showtimeId: selectedShowtimeId
            }, '*');
        }
    }

    window.receiveSeats = function(data) {
        console.log('Data received in parent window:', data);
        selectedSeats = Array.isArray(data.seats) ? data.seats : [];
        cinemaName = data.cinemaName || 'N/A'; // Nhận cinemaName từ iframe
        sessionStorage.setItem('cinemaName', cinemaName); // Lưu vào sessionStorage
        console.log('Updated selectedSeats and cinemaName in parent:', { selectedSeats, cinemaName });
        updateOrderSummary();
    };

    function myFunction(id, date) {
        document.getElementById(prevId).style.background = "rgb(243, 235, 235)";
        document.getElementById(id).style.background = "#df0e62";
        prevId = id;
        selectedDate = date;
        selectedTime = null;
        selectedShowtimeId = null;
        selectedSeats = []; // Reset selected seats when changing date
        document.getElementById("screen-next-btn").disabled = true;
        const iframe = document.getElementById('seat-map-iframe');
        const placeholder = document.getElementById('seat-map-placeholder');
        if (iframe) iframe.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
        updateShowtimes(date);
        updateOrderSummary();
    }

    function updateShowtimes(date) {
        console.log('Date:', date);
        console.log('Showtimes Data:', showtimesData);
        const timeUl = document.getElementById('time-ul');
        timeUl.innerHTML = '';
        const rooms = showtimesData[date] || [];
        if (rooms.length === 0) {
            timeUl.innerHTML = '<li class="time-li">Không có suất chiếu nào cho ngày này.</li>';
            return;
        }
        rooms.forEach(room => {
            const li = document.createElement('li');
            li.className = 'time-li';
            li.innerHTML = `
                <div class="screens">${room.room_name}</div>
                <div class="time-btn">
                    ${room.times.map(time => `
                        <button class="screen-time" onclick="timeFunction(${time.id}, '${time.time}', ${time.base_price})">${time.time}</button>
                    `).join('')}
                </div>
            `;
            timeUl.appendChild(li);
        });
    }

    function timeFunction(showtimeId, time, basePrice) {
        console.log('Executing timeFunction with showtimeId:', showtimeId);
        selectedTime = time;
        selectedShowtimeId = showtimeId;
        ticketPrice = basePrice;
        selectedSeats = []; // Reset selected seats when changing showtime
        document.getElementById("screen-next-btn").disabled = false;
        updateOrderSummary();

        // Khởi động timer với thời gian mặc định (10 phút = 600 giây)
        startCountdown(600);

        const iframe = document.getElementById('seat-map-iframe');
        const placeholder = document.getElementById('seat-map-placeholder');
        if (iframe && placeholder) {
            try {
                const newSrc = '{{ route('client.seats.map', ['showtimeId' => ':showtimeId']) }}'.replace(
                    ':showtimeId', showtimeId) + '?t=' + new Date().getTime();
                console.log('Updating iframe src to:', newSrc);
                iframe.src = newSrc;
                iframe.style.display = 'block';
                placeholder.style.display = 'none';
            } catch (error) {
                console.error('Error updating iframe:', error);
            }
        } else {
            console.error('Iframe or placeholder not found in timeFunction');
        }
    }

    function updateVariant(productId) {
        const select = document.querySelector(`.variant-select[data-product-id="${productId}"]`);
        if (!select) return;
        const selectedOption = select.options[select.selectedIndex];
        variantData[productId] = {
            variantName: selectedOption.text.split(' (')[0],
            price: parseFloat(selectedOption.getAttribute('data-price')) || 0
        };
        const quantity = parseInt(document.getElementById(`quantity-${productId}`).value) || 0;
        if (quantity > 0) {
            const productName = select.closest('.flex.flex-col.items-center').querySelector('h4').textContent;
            const variantKey = `${productId}-${variantData[productId].variantName}`;
            updateSummaryTable(variantKey, productName, variantData[productId].variantName, quantity, variantData[productId].price);
            snackTotal = calculateSnackTotal();
            updateOrderSummary();
        }
    }

    function updateQuantity(productId, change) {
        const quantityInput = document.getElementById(`quantity-${productId}`);
        let currentQuantity = parseInt(quantityInput.value) || 0;
        let newQuantity = Math.max(0, currentQuantity + change);
        quantityInput.value = newQuantity;

        const select = document.querySelector(`.variant-select[data-product-id="${productId}"]`);
        if (!select) return;
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const variantName = selectedOption.text.split(' (')[0];
        const productName = select.closest('.flex.flex-col.items-center').querySelector('h4').textContent;
        const variantKey = `${productId}-${variantName}`;

        snackTotal += (newQuantity - currentQuantity) * price;
        updateSummaryTable(variantKey, productName, variantName, newQuantity, price);
        updateOrderSummary();
    }

    function updateSummaryTable(variantKey, productName, variantName, quantity, price) {
        const tableBody = document.getElementById('summary-table-body');
        if (!tableBody) return;
        let row = document.querySelector(`#summary-table-body tr[data-variant-key="${variantKey}"]`);

        if (quantity === 0) {
            if (row) row.remove();
        } else {
            if (!row) {
                row = document.createElement('tr');
                row.setAttribute('data-variant-key', variantKey);
                tableBody.querySelector('tbody')?.appendChild(row) ?? tableBody.appendChild(row);
            }
            row.innerHTML = `
                <td style="padding: 8px; text-align: left;">${productName} (${variantName})</td>
                <td style="padding: 8px; text-align: center;">${quantity}</td>
                <td style="padding: 8px; text-align: right;">${numberFormat(quantity * price)} ₫</td>
            `;
        }
        snackTotal = calculateSnackTotal();
    }

    function calculateSnackTotal() {
        let snackSubtotal = 0;
        document.querySelectorAll('#summary-table-body tr[data-variant-key]').forEach(row => {
            const priceText = row.children[2].textContent.replace(' ₫', '').replace(/,/g, '');
            snackSubtotal += parseFloat(priceText) || 0;
        });
        return snackSubtotal;
    }

    function numberFormat(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatDateDisplay(dateStr) {
        const dateObj = new Date(dateStr);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function updateOrderSummary() {
        const elements = {
            ticketCount: document.getElementById('ticket-count'),
            ticketPrice: document.getElementById('ticket-price'),
            selectedSeats: document.getElementById('selected-seats'),
            selectedShowtime: document.getElementById('selected-showtime'),
            discountAmount: document.getElementById('discount-amount'),
            orderTotal: document.getElementById('order-total'),
            totalAmount: document.getElementById('total-amount'),
            ticketScreen: document.getElementById('ticket-screen'),
            ticketRow: document.getElementById('ticket-row'),
            ticketSeat: document.getElementById('ticket-seat'),
            ticketPriceDisplay: document.getElementById('ticket-price-display'),
            ticketDate: document.getElementById('ticket-date'),
            ticketTime: document.getElementById('ticket-time'),
            movieTitle: document.getElementById('movie-title'),
            cinemaName: document.getElementById('cinema-name'),
            roomName: document.getElementById('room-name'),
            ticketScreenDisplay: document.getElementById('ticket-screen-display'),
            ticketRowDisplay: document.getElementById('ticket-row-display'),
            ticketSeatDisplay: document.getElementById('ticket-seat-display'),
            ticketPriceFinal: document.getElementById('ticket-price-final'),
            ticketDateDisplay: document.getElementById('ticket-date-display'),
            ticketTimeDisplay: document.getElementById('ticket-time-display'),
            subtotalDisplay: document.getElementById('subtotal-display'),
            discountDisplay: document.getElementById('discount-display'),
            totalDisplay: document.getElementById('total-display'),
            summaryCinemaName: document.getElementById('summary-cinema-name'),
            snackItems: document.getElementById('snack-items'),
            snackTotal: document.getElementById('snack-total')
        };

        // Log để debug
        console.log('Selected seats before update:', JSON.stringify(selectedSeats));

        // Sử dụng biến toàn cục cinemaName, chỉ tính lại nếu cần
        let roomName = 'N/A';
        if (selectedShowtimeId && showtimesData[selectedDate]) {
            for (const room of showtimesData[selectedDate]) {
                const showtime = room.times.find(time => time.id == selectedShowtimeId);
                if (showtime) {
                    roomName = room.room_name;
                    const roomData = roomsData.find(r => r.name === roomName);
                    if (roomData && roomData.cinema && roomData.cinema.name && cinemaName === 'N/A') {
                        cinemaName = roomData.cinema.name;
                    }
                    break;
                }
            }
        }

        // Cập nhật summary-cinema-name
        if (elements.summaryCinemaName) {
            elements.summaryCinemaName.textContent = cinemaName;
        } else {
            console.warn('Element summary-cinema-name not found in DOM');
        }

        const ticketCount = selectedSeats.length;
        let ticketTotal = selectedSeats.reduce((sum, seat) => sum + (seat.price || ticketPrice), 0);

        for (let key in elements) {
            if (elements[key]) {
                switch (key) {
                    case 'ticketCount':
                        elements[key].textContent = ticketCount;
                        break;
                    case 'ticketPrice':
                        elements[key].textContent = numberFormat(ticketTotal) + ' ₫';
                        break;
                    case 'selectedSeats':
                        elements[key].textContent = selectedSeats.map(seat => seat.label || 'N/A').join(', ') || 'N/A';
                        break;
                    case 'selectedShowtime':
                        const formattedTime = selectedTime ? selectedTime.replace(/(AM|PM)/i, '').trim() : '';
                        elements[key].textContent = formattedTime && selectedDate ? `${formattedTime} ${formatDateDisplay(selectedDate)}` : 'N/A';
                        break;
                    case 'discountAmount':
                        elements[key].textContent = numberFormat(discount) + ' ₫';
                        break;
                    case 'orderTotal':
                    case 'totalAmount':
                        const total = ticketTotal + snackTotal - discount;
                        elements[key].textContent = numberFormat(total) + ' ₫';
                        break;
                    case 'ticketScreen':
                        elements[key].textContent = roomName;
                        break;
                    case 'ticketRow':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => (seat.label || 'N/A').split('-')[0]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketSeat':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => (seat.label || 'N/A').split('-')[1]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketPriceDisplay':
                        elements[key].textContent = numberFormat(ticketTotal) + ' ₫';
                        break;
                    case 'ticketDate':
                        elements[key].textContent = selectedDate || 'N/A';
                        break;
                    case 'ticketTime':
                        elements[key].textContent = selectedTime || 'N/A';
                        break;
                    case 'movieTitle':
                        elements[key].textContent = movieTitle || 'N/A';
                        break;
                    case 'cinemaName':
                        elements[key].textContent = cinemaName;
                        break;
                    case 'roomName':
                        elements[key].textContent = roomName;
                        break;
                    case 'ticketScreenDisplay':
                        elements[key].textContent = roomName;
                        break;
                    case 'ticketRowDisplay':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => (seat.label || 'N/A').split('-')[0]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketSeatDisplay':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => (seat.label || 'N/A').split('-')[1]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketPriceFinal':
                        elements[key].textContent = numberFormat(ticketTotal) + ' ₫';
                        break;
                    case 'ticketDateDisplay':
                        elements[key].textContent = selectedDate || 'N/A';
                        break;
                    case 'ticketTimeDisplay':
                        elements[key].textContent = selectedTime || 'N/A';
                        break;
                    case 'subtotalDisplay':
                        elements[key].textContent = numberFormat(ticketTotal + snackTotal) + ' ₫';
                        break;
                    case 'discountDisplay':
                        elements[key].textContent = numberFormat(discount) + ' ₫';
                        break;
                    case 'totalDisplay':
                        elements[key].textContent = numberFormat(ticketTotal + snackTotal - discount) + ' ₫';
                        break;
                    case 'summaryCinemaName':
                        elements[key].textContent = cinemaName;
                        break;
                    case 'snackItems':
                        elements[key].textContent = snackTotal > 0 ? document.querySelectorAll('#summary-table-body tr[data-product-id]').length > 0 ? Array.from(document.querySelectorAll('#summary-table-body tr[data-product-id]')).map(row => row.children[0].textContent).join(', ') : 'Chưa chọn đồ ăn' : 'Chưa chọn đồ ăn';
                        break;
                    case 'snackTotal':
                        elements[key].textContent = numberFormat(snackTotal) + ' ₫';
                        break;
                }
            } else {
                console.warn(`Element ${key} not found in DOM`);
            }
        }
        const summaryTotalElement = document.getElementById('summary-total');
        if (summaryTotalElement) {
            const totalAmount = ticketTotal + snackTotal - discount;
            summaryTotalElement.textContent = numberFormat(totalAmount) + ' ₫';
        }
    }

    function handleNextStep(e) {
        e.preventDefault();
        if (currentStep === 1 && !selectedShowtimeId) {
            alert('Vui lòng chọn suất chiếu trước khi tiếp tục.');
            return;
        }
        if (currentStep < 5) {
            console.log('Moving to step:', currentStep + 1, 'with selected seats:', selectedSeats, 'cinemaName:', cinemaName);
            currentStep++;
            showStep(currentStep);
            updateOrderSummary();
        }
    }

    function handlePreviousStep(e) {
        e.preventDefault();
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            console.log('Moving to step:', currentStep, 'with selected seats:', selectedSeats);
        }
    }

    function showStep(step) {
        document.querySelectorAll('fieldset').forEach((fieldset, index) => {
            fieldset.style.display = index === step - 1 ? 'block' : 'none';
        });
        document.querySelectorAll('#progressbar li').forEach((li, index) => {
            li.classList.remove('active');
            if (index < step) li.classList.add('active');
        });

        // Cập nhật hiển thị timer cho bước 3 và 4
        if (step === 3 || step === 4) {
            if (countdownInterval) {
                updateTimerDisplay(); // Cập nhật hiển thị nếu timer đang chạy
            }
        } else if (step === 1) {
            // Dừng timer nếu quay lại bước 1
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                ['hours-snack', 'minutes-snack', 'seconds-snack', 'hours-payment', 'minutes-payment', 'seconds-payment'].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = "00";
                });
            }
        }

        console.log('Displaying step:', step);
    }
</script>

<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'>
</script>
<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>