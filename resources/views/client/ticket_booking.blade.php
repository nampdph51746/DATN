@extends('layouts.client.client')
@section('content')
    <div class="container" id="progress-container-id">
        <div class="row">
            <div class="col">
                <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                    <!-- Loại bỏ thẻ <form> để tránh kiểm tra hợp lệ tự động -->
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
                                <h2>Show time Selection</h2>
                                <div class="carousel carousel-nav" data-flickity='{"contain": true, "pageDots": false }'>
                                    <div class="carousel-cell" id="1" onclick="myFunction(1)">
                                        <div class="date-numeric">13</div>
                                        <div class="date-day">Today</div>
                                    </div>
                                    <div class="carousel-cell" id="2" onclick="myFunction(2)">
                                        <div class="date-numeric">14</div>
                                        <div class="date-day">Tomorrow</div>
                                    </div>
                                    <div class="carousel-cell" id="3" onclick="myFunction(3)">
                                        <div class="date-numeric">15</div>
                                        <div class="date-day">Monday</div>
                                    </div>
                                    <div class="carousel-cell" id="4" onclick="myFunction(4)">
                                        <div class="date-numeric">16</div>
                                        <div class="date-day">Tuesday</div>
                                    </div>
                                    <div class="carousel-cell" id="5" onclick="myFunction(5)">
                                        <div class="date-numeric">17</div>
                                        <div class="date-day">Wednesday</div>
                                    </div>
                                    <div class="carousel-cell" id="6" onclick="myFunction(6)">
                                        <div class="date-numeric">18</div>
                                        <div class="date-day">Thursday</div>
                                    </div>
                                    <div class="carousel-cell" id="7" onclick="myFunction(7)">
                                        <div class="date-numeric">19</div>
                                        <div class="date-day">Friday</div>
                                    </div>
                                </div>
                                <ul class="time-ul">
                                    <li class="time-li">
                                        <div class="screens">Screen 1</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="timeFunction()">1:05 PM</button>
                                            <button class="screen-time" onclick="timeFunction()">4:00 PM</button>
                                            <button class="screen-time" onclick="timeFunction()">9:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 2</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="timeFunction()">3:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 3</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="timeFunction()">9:05 AM</button>
                                            <button class="screen-time" onclick="timeFunction()">10:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 4</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="timeFunction()">9:05 AM</button>
                                            <button class="screen-time" onclick="timeFunction()">11:00 AM</button>
                                            <button class="screen-time" onclick="timeFunction()">3:00 PM</button>
                                            <button class="screen-time" onclick="timeFunction()">7:00 PM</button>
                                            <button class="screen-time" onclick="timeFunction()">10:00 PM</button>
                                            <button class="screen-time" onclick="timeFunction()">11:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 5</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="timeFunction()">9:05 AM</button>
                                            <button class="screen-time" onclick="timeFunction()">12:00 PM</button>
                                            <button class="screen-time" onclick="timeFunction()">1:00 PM</button>
                                            <button class="screen-time" onclick="timeFunction()">3:00 PM</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <input id="screen-next-btn" type="button" name="next-step" class="next-step" value="Continue Booking" disabled />
                        </fieldset>
                        <fieldset>
                            <div>
                                <iframe id="seat-sel-iframe" style="box-shadow: 0 14px 12px 0 var(--theme-border), 0 10px 50px 0 var(--theme-border); width: 800px; height: 550px; display: block; margin-left: auto; margin-right: auto;" src="seat_selection/seat_sel.html"></iframe>
                            </div>
                            <br>
                            <input type="button" name="next-step" class="next-step" value="Proceed to Snacks" />
                            <input type="button" name="previous-step" class="previous-step" value="Back" />
                        </fieldset>
<!-- Bước 3: Snack Selection -->
<fieldset>
    <div id="snack-select-div">
        <h2>Snack Selection</h2>
        <div class="snack-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px; background: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="snack-item" style="display: flex; flex-direction: column; align-items: center; background: white; padding: 15px; border-radius: 8px; text-align: center;">
                <img src="client_assets/assets/images/popcorn_combo.png" alt="Popcorn Combo" style="margin-bottom: 10px; width: 120px; height: 120px; object-fit: cover;" />
                <div class="snack-info">
                    <h4>Popcorn Combo (Large Popcorn + Drink)</h4>
                    <p>Price: $10.00</p>
                </div>
                <div class="snack-quantity" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <button onclick="updateQuantity('popcorn', -1)" style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">-</button>
                    <input type="number" id="popcorn-quantity" value="0" min="0" style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;" readonly />
                    <button onclick="updateQuantity('popcorn', 1)" style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">+</button>
                </div>
            </div>
            <div class="snack-item" style="display: flex; flex-direction: column; align-items: center; background: white; padding: 15px; border-radius: 8px; text-align: center;">
                <img src="client_assets/assets/images/coke.png" alt="Coke (Medium)" style="margin-bottom: 10px; width: 120px; height: 120px; object-fit: cover;" />
                <div class="snack-info">
                    <h4>Coke (Medium)</h4>
                    <p>Price: $5.00</p>
                </div>
                <div class="snack-quantity" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <button onclick="updateQuantity('coke', -1)" style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">-</button>
                    <input type="number" id="coke-quantity" value="0" min="0" style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;" readonly />
                    <button onclick="updateQuantity('coke', 1)" style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">+</button>
                </div>
            </div>
            <div class="snack-item" style="display: flex; flex-direction: column; align-items: center; background: white; padding: 15px; border-radius: 8px; text-align: center;">
                <img src="client_assets/assets/images/nachos_combo.jpg" alt="Nachos Combo" style="margin-bottom: 10px; width: 120px; height: 120px; object-fit: cover;" />
                <div class="snack-info">
                    <h4>Nachos Combo (Nachos + Cheese Dip)</h4>
                    <p>Price: $8.00</p>
                </div>
                <div class="snack-quantity" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <button onclick="updateQuantity('nachos', -1)" style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">-</button>
                    <input type="number" id="nachos-quantity" value="0" min="0" style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;" readonly />
                    <button onclick="updateQuantity('nachos', 1)" style="padding: 5px 10px; background: #ff4b5a; color: white; border: none; border-radius: 5px;">+</button>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <input type="button" name="previous-step" class="previous-step" value="Back" style="padding: 10px 20px; background: #e0e0e0; color: #333; border: none; border-radius: 5px;" />
            <input type="button" name="next-step" class="next-step" value="Proceed to Payment" style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 5px;" />
        </div>
    </div>
</fieldset>

<!-- Bước 4: Payment -->
<fieldset>
    <div class="payment-container step-content" style="display: flex; justify-content: space-between; padding: 20px; gap: 20px; background: #ffffff; border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); max-width: 1200px; margin: 0 auto;">
        <!-- Left Section: Payment Details -->
        <div style="flex: 2; padding: 20px; background: #f9f9f9; border-radius: 8px; border: 1px solid #e9ecef;">
            <h2 style="color: #1a1a1a; font-size: 1.6em; font-weight: 600; margin-bottom: 20px;">Payment Details</h2>
            
            <!-- Voucher Section -->
            <div style="margin-bottom: 20px;">
                <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 10px;">Voucher</h3>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="text" placeholder="Enter voucher code" style="flex: 1; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 1em;" />
                    <button style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.3s;">Apply</button>
                </div>
            </div>

            <!-- Reward Points Section -->
            <div style="margin-bottom: 20px;">
                <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 10px;">Reward Points</h3>
                <p style="color: #6c757d; margin-bottom: 10px;">Available Points: <span style="color: #ff4b5a; font-weight: 600;">500</span></p>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="number" placeholder="Enter points to use" min="0" max="500" style="flex: 1; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 1em;" />
                    <button style="padding: 10px 20px; background: #ff4b5a; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.3s;">Redeem</button>
                </div>
            </div>

            <!-- Payment Methods Section -->
            <div>
                <h3 style="color: #343a40; font-size: 1.2em; margin-bottom: 15px;">Payment Methods</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                        <input type="radio" name="payment-method" style="margin-right: 12px; transform: scale(1.3);" />
                        <span style="flex-grow: 1; color: #1a1a1a;">Credit/Debit Card</span>
                        <span>
                            <i class="fa fa-cc-visa" style="color: #1a1a1a; margin-right: 8px; font-size: 1.2em;"></i>
                            <i class="fa fa-cc-mastercard" style="color: #1a1a1a; margin-right: 8px; font-size: 1.2em;"></i>
                        </span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
                        <input type="radio" name="payment-method" style="margin-right: 12px; transform: scale(1.3);" />
                        <span style="flex-grow: 1; color: #1a1a1a;">PayPal</span>
                        <span>
                            <i class="fa fa-paypal" style="color: #1a1a1a; font-size: 1.2em;"></i>
                        </span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 12px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px; transition: all 0.3s ease;">
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
        <div style="flex: 1; padding: 20px; background: #f9f9f9; border-radius: 8px; border: 1px solid #e9ecef; text-align: center;">
            <h3 style="color: #1a1a1a; font-size: 1.4em; font-weight: 600; margin-bottom: 20px;">Payment Summary</h3>
            <ul style="list-style: none; padding: 0; margin-bottom: 20px; text-align: left;">
                <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Subtotal: <span style="color: #ff4b5a; font-weight: 600;">$50.00</span></li>
                <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Discount: <span style="color: #28a745; font-weight: 600;">-$5.00</span></li>
                <li style="margin-bottom: 12px; color: #495057; font-size: 1.1em;">Points Used: <span style="color: #28a745; font-weight: 600;">-100 pts</span></li>
                <li style="margin-bottom: 20px; color: #495057; font-size: 1.1em; border-top: 1px solid #e9ecef; padding-top: 12px;">
                    Total: <span style="color: #ff4b5a; font-weight: 600; font-size: 1.2em;">$45.00</span>
                </li>
            </ul>
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                <h2 style="color: #1a1a1a; font-size: 1.2em; margin-bottom: 10px;">Time Remaining</h2>
                <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;">
                    <span style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;">0</span>
                    <span style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;">7</span>
                    <span style="background: #ff4b5a; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;">47</span>
                </div>
                <p style="color: #6c757d; font-size: 0.9em;">Hours Minutes Seconds</p>
            </div>
        </div>
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <input type="button" name="previous-step" class="previous-step" value="Back" style="padding: 12px 30px; background: #6c757d; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;" />
        <input type="button" name="next-step" class="next-step pay-btn" value="Confirm Payment" style="padding: 12px 30px; background: #ff4b5a; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;" />
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
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/25240/only-god-forgives.jpg" alt="Movie: Only God Forgives" />
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
        <input type="button" name="previous-step" class="previous-step home-page-btn" value="Browse to Home Page" style="padding: 12px 30px; background: #6c757d; color: #fff; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; transition: background 0.3s;" onclick="location.href='index.html';" />
    </div>
</fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    let currentStep = 1;
    let prevId = "1";
    let selectedTime = null;

    window.onload = function () {
        showStep(currentStep);
        document.getElementById("screen-next-btn").disabled = true;
        // Gắn sự kiện cho các nút
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', handleNextStep);
        });
        document.querySelectorAll('.previous-step').forEach(button => {
            button.addEventListener('click', handlePreviousStep);
        });
    }

    // Hàm nhận dữ liệu ghế từ iframe (để tránh lỗi, nhưng không sử dụng)
    window.receiveSeats = function(seats) {
        console.log('Seats received:', seats); // Chỉ log để debug
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

    function handleNextStep(e) {
        e.preventDefault();
        if (currentStep < 5) {
            currentStep++;
            showStep(currentStep);
            console.log('Moving to step:', currentStep);
            if (currentStep === 2) {
                document.getElementById('seat-sel-iframe').src = 'seat_selection/seat_sel.html';
            }
        }
    }

    function handlePreviousStep(e) {
        e.preventDefault();
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            console.log('Moving to step:', currentStep);
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

<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
<script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'></script>
<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src="assets/js/theme-change.js"></script>
<script type="text/javascript" src="assets/js/ticket-booking.js"></script>