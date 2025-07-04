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
                        <!-- Step 1: Show Time Selection -->
                        <fieldset>
                            <div id="screen-select-div">
                                <h2>Show time Selection</h2>
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
                        <!-- Step 2: Seat Selection -->
                        <fieldset>
                            <div>
<<<<<<< HEAD
                                <iframe id="seat-sel-iframe"
                                    style="box-shadow: 0 14px 12px 0 var(--theme-border), 0 10px 50px 0 var(--theme-border); width: 800px; height: 550px; display: block; margin-left: auto; margin-right: auto;"
                                    src="seat_selection/seat_sel.html"></iframe>
=======
                                <h2>Seat Selection</h2>
                                <iframe id="seat-map-iframe" src="" width="100%" height="700"
                                    style="border: none; overflow: hidden; display: none;" onload="onIframeLoad()">
                                </iframe>
                                <div id="seat-map-placeholder" style="display: block;">
                                    <p>Please select a showtime to view the seat map.</p>
                                </div>
>>>>>>> origin/Giang
                            </div>
                            <br>
                            <input type="button" name="next-step" id="proceed-snack-btn" class="next-step"
                                value="Proceed to Snacks" />
                            <input type="button" name="previous-step" id="back-btn" class="previous-step"
                                value="Back" />
                        </fieldset>
                        <!-- Bước 3: Snack Selection -->
                        <fieldset>
                            <div id="snack-select-div" style="display: flex; gap: 20px; padding: 20px;">
                                <!-- Left side: Snack Cards -->
                                <div
                                    style="flex: 2; background: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px;">
                                    <h2>Snack Selection</h2>
                                    <div class="snack-container"
                                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                        @foreach ($products as $product)
                                            <div class="snack-item"
                                                style="display: flex; flex-direction: column; align-items: center; background: white; padding: 15px; border-radius: 8px; text-align: center;">
                                                <img src="{{ asset('storage/' . $product->image_url) }}"
                                                    alt="{{ $product->name }}"
                                                    style="margin-bottom: 10px; width: 120px; height: 120px; object-fit: cover;" />
                                                <div class="snack-info">
                                                    <h4>{{ $product->name }}</h4>
                                                    @if ($product->productVariants->isNotEmpty())
                                                        <select class="variant-select form-select"
                                                            data-product-id="{{ $product->id }}"
                                                            style="margin-top: 10px; padding: 5px; border-radius: 5px;"
                                                            onchange="updateQuantity('{{ $product->id }}', 0)">
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
                                                        <p>No variants available</p>
                                                    @endif
                                                </div>
                                                <div class="snack-quantity"
                                                    style="display: flex; align-items: center; gap: 10px; margin-top: 10px;"
                                                    data-product-id="{{ $product->id }}">
                                                    <button onclick="updateQuantity('{{ $product->id }}', -1)"
                                                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">-</button>
                                                    <input type="number" id="quantity-{{ $product->id }}" value="0"
                                                        min="0"
                                                        style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;"
                                                        readonly />
                                                    <button onclick="updateQuantity('{{ $product->id }}', 1)"
                                                        style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Right side: Summary Table -->
                                <div
                                    style="flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 15px;">
                                    <h3 style="color: #333; margin-bottom: 10px;">Order Summary</h3>
                                    <table id="summary-table" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #eee;">
                                                <th style="padding: 8px; text-align: left;">Item</th>
                                                <th style="padding: 8px; text-align: center;">Quantity</th>
                                                <th style="padding: 8px; text-align: right;">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="summary-table-body">
                                            <!-- Movie Ticket Information -->
                                            <tr id="movie-ticket-row" style="border-bottom: 1px solid #eee;">
                                                <td style="padding: 8px; text-align: left;" id="movie-ticket-info">N/A</td>
                                                <td style="padding: 8px; text-align: center;" id="ticket-quantity">0</td>
                                                <td style="padding: 8px; text-align: right;" id="ticket-price">0 ₫</td>
                                            </tr>
                                            <!-- Snack rows will be populated dynamically via JavaScript -->
                                        </tbody>
                                        <tfoot>
                                            <tr style="border-top: 2px solid #333; font-weight: bold;">
                                                <td style="padding: 8px; text-align: left;">Total</td>
                                                <td style="padding: 8px; text-align: center;"></td>
                                                <td id="total-price" style="padding: 8px; text-align: right;">0 ₫</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-top: 20px; padding: 0 20px;">
                                <input type="button" name="previous-step" class="previous-step" value="Back"
                                    style="padding: 10px 20px; background: #e0e0e0; color: #333; border: none; border-radius: 5px;" />
                                <input type="button" name="next-step" class="next-step" value="Proceed to Payment"
                                    style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 5px;" />
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
                                                id="hours">0</span>
                                            <span
                                                style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;"
                                                id="minutes">0</span>
                                            <span
                                                style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;"
                                                id="seconds">0</span>
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
<<<<<<< HEAD
                        <!-- Step 3: Snack Selection -->
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
                                        <div class="snack-quantity"
                                            style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
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
                                        <div class="snack-quantity"
                                            style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
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
                                        <div class="snack-quantity"
                                            style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
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
                        <!-- Step 4: Payment -->
                        <fieldset>
                            <div style="max-width: 1100px; margin: 0 auto; padding: 20px;">
                                <div
                                    style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: flex-start;">
                                    <!-- Left Column: Payment Form -->
                                    <div
                                        style="background: #fff; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.08);">
                                        <!-- Payment Header -->
                                        <div
                                            style="background: linear-gradient(135deg, #d32f2f, #b71c1c); color: #fff; font-weight: 700; text-align: center; padding: 20px 0; border-radius: 16px 16px 0 0; font-size: 1.4em; letter-spacing: 0.5px;">
                                            THANH TOÁN
                                        </div>
                                        <!-- Payment Content -->
                                        <div style="padding: 30px;">
                                            <!-- Step 1: Discounts -->
                                            <div
                                                style="background: #fff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;">
                                                <div
                                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <div
                                                            style="background: #d32f2f; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                            1
                                                        </div>
                                                        <span
                                                            style="font-weight: 700; color: #d32f2f; font-size: 1.2em;">GIẢM
                                                            GIÁ</span>
                                                    </div>
                                                    <button type="button"
                                                        style="background: none; border: none; color: #d32f2f; cursor: pointer; font-size: 0.95em; display: flex; align-items: center; gap: 6px; font-weight: 500;">
                                                        <i class="fa fa-refresh"></i> Đặt lại
                                                    </button>
                                                </div>
                                                <div style="margin-top: 16px; display: grid; gap: 16px;">
                                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                                        <label style="color: #555; font-size: 0.95em;">CGV Voucher</label>
                                                        <input type="text"
                                                            style="width: 100%; border-radius: 8px; border: 1px solid #ddd; padding: 12px 16px; font-size: 1em;"
                                                            placeholder="Nhập mã voucher">
                                                    </div>
                                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                                        <label style="color: #555; font-size: 0.95em;">Điểm thành
                                                            viên</label>
                                                        <input type="number"
                                                            style="width: 100%; border-radius: 8px; border: 1px solid #ddd; padding: 12px 16px; font-size: 1em;"
                                                            placeholder="Nhập điểm">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Step 2: Payment Methods -->
                                            <div
                                                style="background: #fff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;">
                                                <div
                                                    style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                                                    <div
                                                        style="background: #d32f2f; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                        2
                                                    </div>
                                                    <span style="font-weight: 700; color: #d32f2f; font-size: 1.2em;">HÌNH
                                                        THỨC THANH TOÁN</span>
                                                </div>
                                                <div style="margin-top: 16px; display: grid; gap: 12px;">
                                                    <div
                                                        style="background: #f9f9f9; border-radius: 10px; padding: 16px; border: 1px solid #eee; transition: all 0.2s; cursor: pointer;">
                                                        <label
                                                            style="display: flex; align-items: center; gap: 16px; cursor: pointer; margin: 0;">
                                                            <input type="radio" name="payment_method"
                                                                style="width: 1.3em; height: 1.3em; accent-color: #d32f2f;">
                                                            <div
                                                                style="display: flex; align-items: center; gap: 12px; width: 100%;">
                                                                <img src="client_assets/assets/images/momo.png"
                                                                    width="40" style="border-radius: 4px;">
                                                                <div>
                                                                    <div style="font-weight: 600; color: #333;">MOMO</div>
                                                                    <div style="font-size: 0.85em; color: #666;">Thanh toán
                                                                        qua ví điện tử MoMo</div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div
                                                        style="background: #f9f9f9; border-radius: 10px; padding: 16px; border: 1px solid #eee; transition: all 0.2s; cursor: pointer;">
                                                        <label
                                                            style="display: flex; align-items: center; gap: 16px; cursor: pointer; margin: 0;">
                                                            <input type="radio" name="payment_method"
                                                                style="width: 1.3em; height: 1.3em; accent-color: #d32f2f;">
                                                            <div
                                                                style="display: flex; align-items: center; gap: 12px; width: 100%;">
                                                                <img src="client_assets/assets/images/vnpay.jpg"
                                                                    width="40" style="border-radius: 4px;">
                                                                <div>
                                                                    <div style="font-weight: 600; color: #333;">VNPAY</div>
                                                                    <div style="font-size: 0.85em; color: #666;">Thanh toán
                                                                        qua cổng VNPAY</div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Terms and Submit -->
                                            <div style="margin-top: 24px;">
                                                <div
                                                    style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 24px;">
                                                    <input type="checkbox" id="agree"
                                                        style="width: 1.3em; height: 1.3em; accent-color: #d32f2f; margin-top: 3px;">
                                                    <label for="agree" style="color: #555; line-height: 1.5;">
                                                        Tôi đồng ý với <a href="#"
                                                            style="color: #d32f2f; text-decoration: underline;">điều khoản
                                                            sử dụng</a> và mua vé cho người có độ tuổi phù hợp
                                                    </label>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; gap: 10px;">
                                                    <input type="button" name="previous-step" class="previous-step"
                                                        value="Back"
                                                        style="flex: 1; padding: 12px; background: #e0e0e0; color: #333; border: none; border-radius: 10px; font-size: 1em; cursor: pointer; transition: background 0.3s;" />
                                                    <button type="button" class="next-step"
                                                        style="flex: 2; padding: 16px; font-weight: 700; font-size: 1.1em; background: linear-gradient(135deg, #d32f2f, #b71c1c); color: white; border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(211,47,47,0.3); transition: all 0.3s; cursor: pointer;">
                                                        THANH TOÁN 120.000 ₫
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column: Order Summary -->
                                    <div style="display: flex; flex-direction: column; gap: 20px;">
                                        <!-- Order Summary -->
                                        <div
                                            style="background: #fff; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); flex: 1;">
                                            <div
                                                style="background: linear-gradient(135deg, #333, #222); color: #fff; font-weight: 700; text-align: center; padding: 18px 0; border-radius: 16px 16px 0 0; font-size: 1.2em;">
                                                Chi tiết đơn hàng
                                            </div>
                                            <div style="padding: 24px;">
                                                <!-- Movie Info -->
                                                <div
                                                    style="display: flex; gap: 16px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                                    <img src="https://via.placeholder.com/80x120"
                                                        style="width: 80px; height: 120px; border-radius: 8px; object-fit: cover;">
                                                    <div>
                                                        <div
                                                            style="font-weight: 700; margin-bottom: 6px; font-size: 1.1em;">
                                                            Tên phim</div>
                                                        <div style="font-size: 0.9em; color: #666; margin-bottom: 4px;">2D
                                                            Phụ đề</div>
                                                        <div style="font-size: 0.9em; color: #666;">Rạp: CGV Vincom</div>
                                                    </div>
                                                </div>
                                                <!-- Ticket Details -->
                                                <div
                                                    style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                                    <div
                                                        style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                                        <span style="color: #555;">Vé người lớn x 1</span>
                                                        <span style="font-weight: 500;">120.000 ₫</span>
                                                    </div>
                                                    <div
                                                        style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                                        <span style="color: #555;">Ghế: D5</span>
                                                        <span style="font-weight: 500;"></span>
                                                    </div>
                                                    <div style="display: flex; justify-content: space-between;">
                                                        <span style="color: #555;">Suất chiếu: 18:30 - 21/06</span>
                                                        <span style="font-weight: 500;"></span>
                                                    </div>
                                                </div>
                                                <!-- Promotions -->
                                                <div
                                                    style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                                    <div style="font-weight: 600; margin-bottom: 12px; color: #333;">Khuyến
                                                        mãi</div>
                                                    <div style="display: flex; justify-content: space-between;">
                                                        <span style="color: #555;">Giảm giá</span>
                                                        <span style="font-weight: 500;">0 ₫</span>
                                                    </div>
                                                </div>
                                                <!-- Total -->
                                                <div>
                                                    <div
                                                        style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1em;">
                                                        <span>Tổng cộng:</span>
                                                        <span style="color: #d32f2f;">120.000 ₫</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Countdown Timer -->
                                        <div
                                            style="background: #fff; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.08);">
                                            <div
                                                style="background: linear-gradient(135deg, #333, #222); color: #fff; font-weight: 700; text-align: center; padding: 18px 0; border-radius: 16px 16px 0 0; font-size: 1.2em;">
                                                Thời gian giữ vé
                                            </div>
                                            <div style="padding: 24px; text-align: center;">
                                                <div style="display: flex; justify-content: center; gap: 16px;">
                                                    <div
                                                        style="display: flex; flex-direction: column; align-items: center; background: linear-gradient(135deg, #d32f2f, #b71c1c); color: #fff; border-radius: 12px; padding: 16px 24px; min-width: 80px;">
                                                        <span id="minutes"
                                                            style="font-size: 2em; font-weight: 700;">04</span>
                                                        <small style="font-size: 0.85em; opacity: 0.9;">Phút</small>
                                                    </div>
                                                    <div
                                                        style="display: flex; flex-direction: column; align-items: center; background: linear-gradient(135deg, #d32f2f, #b71c1c); color: #fff; border-radius: 12px; padding: 16px 24px; min-width: 80px;">
                                                        <span id="seconds"
                                                            style="font-size: 2em; font-weight: 700;">49</span>
                                                        <small style="font-size: 0.85em; opacity: 0.9;">Giây</small>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 16px; font-size: 0.9em; color: #666;">
                                                    Vé sẽ được giải phóng khi hết thời gian
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                // Countdown timer
                                let minutes = 4,
                                    seconds = 49;
                                const minutesElement = document.getElementById('minutes');
                                const secondsElement = document.getElementById('seconds');

                                if (minutesElement && secondsElement) {
                                    function updateTimer() {
                                        if (seconds === 0) {
                                            if (minutes === 0) {
                                                minutesElement.innerText = '00';
                                                secondsElement.innerText = '00';
                                                return;
                                            }
                                            minutes--;
                                            seconds = 59;
                                        } else {
                                            seconds--;
                                        }
                                        minutesElement.innerText = minutes.toString().padStart(2, '0');
                                        secondsElement.innerText = seconds.toString().padStart(2, '0');
                                    }
                                    setInterval(updateTimer, 1000);
                                } else {
                                    console.error('Timer elements not found');
                                }
                            </script>
                        </fieldset>
                        <!-- Step 5: E-Ticket -->
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
                                        <img src="https://s3-us-west-2.amazonaws.com/2.amazonaws.com/s/252/405/40/only-god-forgives.jpg"
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
                            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                                <input type="button" name="previous-step" class="previous-step" value="Back"
                                    style="padding: 12px 30px; background: #6c757d; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;" />
                                <input type="button" name="complete-step" class="complete-step"
                                    value="Complete and Return Home"
                                    style="padding: 12px 30px; background: #ff4b5a; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;"
                                    onclick="location.href='index.html';" />
                            </div>
                        </fieldset>
=======
>>>>>>> origin/Giang
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

    <script>
        let currentStep = 1;
        let prevId = "1";
        let selectedTime = null;

        document.addEventListener('DOMContentLoaded', function() {
            showStep(currentStep);
            document.getElementById("screen-next-btn").disabled = true;

            // Event delegation for navigation buttons
            document.getElementById('form').addEventListener('click', function(e) {
                if (e.target.classList.contains('next-step')) {
                    e.preventDefault();
                    if (currentStep < 5) {
                        currentStep++;
                        showStep(currentStep);
                        console.log('Moving to step:', currentStep);
                        if (currentStep === 2) {
                            document.getElementById('seat-sel-iframe').src = 'seat_selection/seat_sel.html';
                        }
                    }
                } else if (e.target.classList.contains('previous-step')) {
                    e.preventDefault();
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                        console.log('Moving to step:', currentStep);
                    }
                }
            });
        });

        window.receiveSeats = function(seats) {
            console.log('Seats received:', seats); // Debug log
        };

        function timeFunction() {
            selectedTime = event.target.textContent;
            document.getElementById("screen-next-btn").disabled = false;
        }

        function myFunction(id) {
            document.getElementById(prevId).style.background = "rgb(243, 235, 235)";
            document.getElementById(id).style.background = "#df0e62";
            prevId = id;
        }

        function updateQuantity(item, change) {
            let quantity = parseInt(document.getElementById(`${item}-quantity`).value) || 0;
            quantity = Math.max(0, quantity + change);
            document.getElementById(`${item}-quantity`).value = quantity;
        }

        function showStep(step) {
            const fieldsets = document.querySelectorAll('fieldset');
            console.log('Total fieldsets:', fieldsets.length); // Debug log
            fieldsets.forEach((fieldset, index) => {
                fieldset.style.display = index === step - 1 ? 'block' : 'none';
            });
            document.querySelectorAll('#progressbar li').forEach((li, index) => {
                li.classList.remove('active');
                if (index < step) li.classList.add('active');
            });
            console.log('Displaying step:', step);
        }
    </script>

    <script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
    <script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'>
    </script>
    <script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src="assets/js/theme-change.js"></script>
    <script type="text/javascript" src="assets/js/ticket-booking.js"></script>
@endsection
<<<<<<< HEAD
=======

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
    const showtimesData = @json($showtimesData);
    const roomsData = @json($roomsData);
    const movieTitle = @json($movie->name ?? 'N/A');

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

        // Initialize summary table on variant change
        document.querySelectorAll('.variant-select').forEach(select => {
            select.addEventListener('change', () => {
                const productId = select.getAttribute('data-product-id');
                updateQuantity(productId, 0);
            });
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

    window.receiveSeats = function(seats) {
        console.log('Seats received in parent window:', seats);
        selectedSeats = Array.isArray(seats) ? seats : [];
        updateOrderSummary();
        console.log('Updated selectedSeats in parent:', selectedSeats);
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

    function updateQuantity(productId, change) {
        const quantityInput = document.getElementById(`quantity-${productId}`);
        let currentQuantity = parseInt(quantityInput.value) || 0;
        let newQuantity = Math.max(0, currentQuantity + change);
        quantityInput.value = newQuantity;

        const select = document.querySelector(`.variant-select[data-product-id="${productId}"]`);
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const variantName = selectedOption.text.split(' (')[0];
        const productName = select.closest('.snack-item').querySelector('h4').textContent;

        snackTotal += (newQuantity - currentQuantity) * price;
        updateSummaryTable(productId, productName, variantName, newQuantity, price);
        updateOrderSummary();
    }

    function updateSummaryTable(productId, productName, variantName, quantity, price) {
        const tableBody = document.getElementById('summary-table-body');
        let row = document.querySelector(`#summary-table-body tr[data-product-id="${productId}"]`);

        if (quantity === 0) {
            if (row) row.remove();
        } else {
            if (!row) {
                row = document.createElement('tr');
                row.setAttribute('data-product-id', productId);
                tableBody.appendChild(row);
            }
            row.innerHTML = `
                <td style="padding: 8px; text-align: left;">${productName} (${variantName})</td>
                <td style="padding: 8px; text-align: center;">${quantity}</td>
                <td style="padding: 8px; text-align: right;">${numberFormat(quantity * price)} ₫</td>
            `;
        }

        let snackSubtotal = 0;
        document.querySelectorAll('#summary-table-body tr[data-product-id]').forEach(row => {
            const priceText = row.children[2].textContent.replace(' ₫', '').replace(/,/g, '');
            snackSubtotal += parseFloat(priceText) || 0;
        });
        snackTotal = snackSubtotal;
        updateOrderSummary();
    }

    function numberFormat(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
            movieTicketInfo: document.getElementById('movie-ticket-info'),
            ticketQuantity: document.getElementById('ticket-quantity'),
            ticketPriceSummary: document.getElementById('ticket-price'),
            totalPrice: document.getElementById('total-price'),
            ticketScreenDisplay: document.getElementById('ticket-screen-display'),
            ticketRowDisplay: document.getElementById('ticket-row-display'),
            ticketSeatDisplay: document.getElementById('ticket-seat-display'),
            ticketPriceFinal: document.getElementById('ticket-price-final'),
            ticketDateDisplay: document.getElementById('ticket-date-display'),
            ticketTimeDisplay: document.getElementById('ticket-time-display'),
            subtotalDisplay: document.getElementById('subtotal-display'),
            discountDisplay: document.getElementById('discount-display'),
            totalDisplay: document.getElementById('total-display')
        };

        // Tìm tên phòng và tên rạp dựa trên selectedShowtimeId
        let roomName = 'N/A';
        let cinemaName = 'N/A';
        if (selectedShowtimeId && showtimesData[selectedDate]) {
            for (const room of showtimesData[selectedDate]) {
                const showtime = room.times.find(time => time.id == selectedShowtimeId);
                if (showtime) {
                    roomName = room.room_name;
                    const roomData = roomsData.find(r => r.name === roomName);
                    cinemaName = roomData ? roomData.cinema.name : 'N/A';
                    break;
                }
            }
        }

        const ticketCount = selectedSeats.length;
        let ticketTotal = selectedSeats.reduce((sum, seat) => sum + (seat.price || ticketPrice), 0);

        if (elements.movieTicketInfo && elements.ticketQuantity && elements.ticketPriceSummary) {
            if (ticketCount > 0) {
                const seatLabels = selectedSeats.map(seat => seat.label).join(', ');
                const showtimeText = selectedTime ? `${selectedTime} - ${selectedDate}` : 'N/A';
                elements.movieTicketInfo.textContent =
                    `${movieTitle} (${roomName}, ${cinemaName}, ${showtimeText}, Ghế: ${seatLabels})`;
                elements.ticketQuantity.textContent = ticketCount;
                elements.ticketPriceSummary.textContent = numberFormat(ticketTotal) + ' ₫';
            } else {
                elements.movieTicketInfo.textContent = 'Chưa chọn vé';
                elements.ticketQuantity.textContent = '0';
                elements.ticketPriceSummary.textContent = '0 ₫';
            }
        }

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
                        elements[key].textContent = selectedSeats.map(seat => seat.label).join(', ') || 'N/A';
                        break;
                    case 'selectedShowtime':
                        elements[key].textContent = selectedTime ? `${selectedTime} - ${selectedDate}` : 'N/A';
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
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => seat.label
                            .split('-')[0]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketSeat':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => seat.label
                            .split('-')[1]).join(', ') || 'N/A' : 'N/A';
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
                    case 'totalPrice':
                        elements[key].textContent = numberFormat(ticketTotal + snackTotal - discount) + ' ₫';
                        break;
                    case 'ticketScreenDisplay':
                        elements[key].textContent = roomName;
                        break;
                    case 'ticketRowDisplay':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => seat.label
                            .split('-')[0]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketSeatDisplay':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => seat.label
                            .split('-')[1]).join(', ') || 'N/A' : 'N/A';
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
                }
            } else {
                console.warn(`Element ${key} not found in DOM`);
            }
        }
    }

    function handleNextStep(e) {
        e.preventDefault();
        if (currentStep < 5) {
            console.log('Moving to step:', currentStep + 1, 'with selected seats:', selectedSeats);
            currentStep++;
            showStep(currentStep);
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
        console.log('Displaying step:', step);
    }
</script>

<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'>
</script>
<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
>>>>>>> origin/Giang
