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
                        <!-- Step 1: Show Time Selection -->
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
                            <input id="screen-next-btn" type="button" name="next-step" class="next-step"
                                value="Continue Booking" disabled />
                        </fieldset>
                        <!-- Step 2: Seat Selection -->
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
                    </div>
                </div>
            </div>
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
