@extends('layouts.client.client')
@section('content')
    <div class="container" id="progress-container-id">
        <div class="row">
            <div class="col">
                <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                    <form id="form" onsubmit="return false;"> <!-- Ngăn submit mặc định -->
                        <ul id="progressbar" class="progressbar-class">
                            <li class="active" id="step1">Show timing selection</li>
                            <li id="step2" class="not_active">Seat Selection</li>
                            <li id="step3" class="not_active">Payment</li>
                            <li id="step4" class="not_active">E-Ticket</li>
                        </ul>
                        <br>
                        <fieldset style="display: block;"> <!-- Bước 1: Show timing selection -->
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
                                            <button class="screen-time" onclick="selectTime('1:05 PM', this)">1:05 PM</button>
                                            <button class="screen-time" onclick="selectTime('4:00 PM', this)">4:00 PM</button>
                                            <button class="screen-time" onclick="selectTime('9:00 PM', this)">9:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 2</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="selectTime('3:00 PM', this)">3:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 3</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="selectTime('9:05 AM', this)">9:05 AM</button>
                                            <button class="screen-time" onclick="selectTime('10:00 PM', this)">10:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 4</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="selectTime('9:05 AM', this)">9:05 AM</button>
                                            <button class="screen-time" onclick="selectTime('11:00 AM', this)">11:00 AM</button>
                                            <button class="screen-time" onclick="selectTime('3:00 PM', this)">3:00 PM</button>
                                            <button class="screen-time" onclick="selectTime('7:00 PM', this)">7:00 PM</button>
                                            <button class="screen-time" onclick="selectTime('10:00 PM', this)">10:00 PM</button>
                                            <button class="screen-time" onclick="selectTime('11:00 PM', this)">11:00 PM</button>
                                        </div>
                                    </li>
                                    <li class="time-li">
                                        <div class="screens">Screen 5</div>
                                        <div class="time-btn">
                                            <button class="screen-time" onclick="selectTime('9:05 AM', this)">9:05 AM</button>
                                            <button class="screen-time" onclick="selectTime('12:00 PM', this)">12:00 PM</button>
                                            <button class="screen-time" onclick="selectTime('1:00 PM', this)">1:00 PM</button>
                                            <button class="screen-time" onclick="selectTime('3:00 PM', this)">3:00 PM</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <input id="screen-next-btn" type="button" name="next-step" class="next-step" value="Continue Booking" disabled />
                        </fieldset>
                        <fieldset style="display: none;"> <!-- Bước 2: Seat Selection -->
                            <div>
                                <iframe id="seat-sel-iframe"
                                    style="box-shadow: 0 14px 12px 0 var(--theme-border), 0 10px 50px 0 var(--theme-border); width: 800px; height: 550px; display: block; margin-left: auto; margin-right: auto;"
                                    src="/seat_selection/seat_sel.html"></iframe>
                            </div>
                            <br>
                            <input type="button" name="next-step" class="next-step" value="Proceed to Payment" />
                            <input type="button" name="previous-step" class="previous-step" value="Back" />
                        </fieldset>
                        <fieldset style="display: none;"> <!-- Bước 3: Payment -->
                            <div id="payment_div" style="display: flex; flex-direction: column; height: 100%;">

                                <!-- Bước 1: Giảm giá -->
                                <div class="payment-row" style="margin-bottom: 20px;">
                                    <h3 id="payment-h3" style="text-align: center; margin-bottom: 10px;">Discount</h3>
                                    <div class="payment-row payment" style="display: flex; justify-content: space-between;">
                                        <div class="col-50 payment">
                                            <label for="discount_code" class="method">
                                                <div class="radio-input">
                                                    <input type="radio" id="discount_code" name="discount_option" />
                                                    Apply Discount Code
                                                    <input type="text" id="code_input" name="code" placeholder="Enter code" style="margin-left: 10px; padding: 5px;" />
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-50 payment">
                                            <label for="reward_points" class="method">
                                                <div class="radio-input">
                                                    <input type="radio" id="reward_points" name="discount_option" />
                                                    Use Reward Points (100 points = $5)
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bước 2: Chọn hình thức thanh toán -->
                                <div class="payment-row" style="margin-bottom: 20px;">
                                    <h3 id="payment-h3" style="text-align: center; margin-bottom: 10px;">Payment Method</h3>
                                    <div class="payment-row payment" style="display: flex; justify-content: space-between;">
                                        <div class="col-50 payment">
                                            <label for="card" class="method card">
                                                <div class="icon-container">
                                                    <i class="fa fa-cc-visa" style="color: navy"></i>
                                                    <i class="fa fa-cc-amex" style="color: blue"></i>
                                                    <i class="fa fa-cc-mastercard" style="color: red"></i>
                                                    <i class="fa fa-cc-discover" style="color: orange"></i>
                                                </div>
                                                <div class="radio-input">
                                                    <input type="radio" id="card" name="payment_method" />
                                                    Pay RS.200.00 with credit card
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-50 payment">
                                            <label for="paypal" class="method paypal">
                                                <div class="icon-container">
                                                    <i class="fa fa-paypal" style="color: navy"></i>
                                                </div>
                                                <div class="radio-input">
                                                    <input id="paypal" type="radio" name="payment_method" checked />
                                                    Pay $30.00 with PayPal
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phần dưới cùng: Thông tin đơn hàng và menu dọc -->
                                <div style="display: flex; flex: 1; margin-top: auto;">
                                    <!-- Thông tin chi tiết đơn hàng (giữa) -->
                                    <div class="col-75" style="flex: 3; padding-right: 20px;">
                                        <h3 id="payment-h3" style="text-align: center; margin-bottom: 10px;">Order Details</h3>
                                        <div class="payment-row">
                                            <div class="col-50">
                                                <p><strong>Showtime:</strong> 7:00 PM, June 23, 2025</p>
                                            </div>
                                            <div class="col-50">
                                                <p><strong>Seats:</strong> A1, A2</p>
                                            </div>
                                        </div>
                                        <div class="payment-row">
                                            <div class="col-50">
                                                <p><strong>Snacks:</strong> Popcorn, Coke</p>
                                            </div>
                                            <div class="col-50">
                                                <p><strong>Total Amount:</strong> $250.00</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Menu dọc bên phải -->
                                    <div class="col-25" style="flex: 1; background: #f9f9f9; padding: 10px; border-left: 1px solid #ccc;">
                                        <h3 id="payment-h3" style="text-align: center; margin-bottom: 10px;">Payment Summary</h3>
                                        <p><strong>Subtotal:</strong> $250.00</p>
                                        <p><strong>Discount:</strong> -$20.00</p>
                                        <p><strong>Total to Pay:</strong> $230.00</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Nút bấm -->
                            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                                <input type="button" name="next-step" class="next-step pay-btn" value="Confirm Payment" />
                                <input type="button" name="previous-step" class="cancel-pay-btn" value="Cancel Payment" onclick="location.href='index.html';" />
                            </div>
                        </fieldset>
                        <fieldset style="display: none;"> <!-- Bước 4: E-Ticket -->
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
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:black;"></td>
                                                <td style="background-color:white;"></td>
                                                <td style="background-color:white;"></td>
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
                            <input type="button" name="previous-step" class="home-page-btn" value="Browse to Home Page" onclick="location.href='index.html';" />
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    let currentStep = 1;
    let selectedDate = null;
    let selectedTime = null;
    let prevId = "1";

    // Khởi tạo khi load trang
    window.onload = function () {
        showStep(currentStep);
        document.getElementById("screen-next-btn").disabled = true;
    };

    // Chọn ngày
    function myFunction(id) {
        document.getElementById(prevId).style.background = "rgb(243, 235, 235)";
        document.getElementById(id).style.background = "#df0e62";
        selectedDate = id; // Lưu ngày được chọn
        prevId = id;
        checkNextButton(); // Kiểm tra nút tiếp theo
    }

    // Chọn thời gian
    function selectTime(time, button) {
        selectedTime = time; // Lưu thời gian được chọn
        document.querySelectorAll('.screen-time').forEach(btn => btn.style.background = '');
        button.style.background = '#df0e62'; // Đánh dấu thời gian được chọn
        timeFunction(); // Bật nút tiếp theo
    }

    // Bật nút khi chọn thời gian
    function timeFunction() {
        document.getElementById("screen-next-btn").disabled = false;
    }

    // Kiểm tra nút tiếp theo
    function checkNextButton() {
        const nextButton = document.getElementById("screen-next-btn");
        nextButton.disabled = !selectedDate || !selectedTime;
    }

    // Chuyển bước
    function showStep(step) {
        document.querySelectorAll('fieldset').forEach((fieldset, index) => {
            fieldset.style.display = index === step - 1 ? 'block' : 'none';
        });
        document.querySelectorAll('#progressbar li').forEach((li, index) => {
            li.classList.remove('active');
            if (index < step) li.classList.add('active');
        });
    }

    // Xử lý nút "Next" và "Back"
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault(); // Ngăn load lại trang
            if (currentStep < 4) {
                currentStep++;
                showStep(currentStep);
                if (currentStep === 2) {
                    // Đảm bảo iframe tải đúng
                    document.getElementById('seat-sel-iframe').src = '/seat_selection/seat_sel.html';
                }
            }
        });
    });

    document.querySelectorAll('.previous-step').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault(); // Ngăn load lại trang
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });
</script>

<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
<script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'></script>
<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src="assets/js/theme-change.js"></script>
<script type="text/javascript" src="assets/js/ticket-booking.js"></script>