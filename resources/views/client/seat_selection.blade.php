@if (isset($showtime) && isset($room) && isset($room['rows']) && isset($room['cols']) && $room['rows'] > 0 && $room['cols'] > 0)
    <div class="seat-selection-wrapper">
        <div class="seat-selection-grid">
            <div class="seat-map-section">
                <h2 class="section-title text-white fw-bold border-bottom border-fuchsia">Phòng: {{ $room['name'] }}</h2>
                <div class="screen bg-gradient-fuchsia text-white">Màn hình</div>

                <div class="seat-map">
                    @foreach ($room['row_chars'] as $row)
                        <div class="seat-row-container">
                            <div class="row-label bg-secondary text-white">{{ $row }}</div>
                            <div class="seat-row" style="grid-template-columns: repeat({{ $room['cols'] }}, 40px);">
                                @for ($col = 1; $col <= $room['cols']; $col++)
                                    @php
                                        $formattedCol = str_pad($col, 2, '0', STR_PAD_LEFT);
                                        $seat = $seats->first(function ($s) use ($row, $formattedCol) {
                                            return $s['label'] === $row . '-' . $formattedCol;
                                        });
                                    @endphp
                                    @if ($seat)
                                        <div class="seat {{ $seat['status'] }} {{ strtolower($seat['seat_type']) }}"
                                            data-seat-id="{{ $seat['seat_id'] }}"
                                            data-label="{{ $seat['label'] }}"
                                            data-type="{{ $seat['seat_type'] }}"
                                            data-price="{{ $seat['price'] }}"
                                            data-original-color="{{ $seat['color_code'] }}"
                                            style="background-color: {{ $seat['color_code'] }};"
                                            onclick="selectSeat(this)">
                                            {{ $col }}
                                        </div>
                                    @else
                                        <div class="seat empty bg-dark-gray"></div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="seat-legend">
                    @foreach ($seatTypes as $type)
                        <div class="legend-item text-gray-light">
                            <span class="legend-color" style="background-color: {{ $type->color_code }};"></span>
                            {{ $type->name }} ({{ number_format($type->price_modifier ?? $showtime->base_price, 0, ',', '.') }} ₫)
                        </div>
                    @endforeach
                    <div class="legend-item text-gray-light">
                        <span class="legend-color selected bg-fuchsia"></span> Đã chọn
                    </div>
                    <div class="legend-item text-gray-light">
                        <span class="legend-color reserved bg-danger"></span> Đã đặt
                    </div>
                </div>
            </div>

            <div class="summary-section bg-dark shadow-lg">
                <h3 class="summary-title text-white fw-semibold border-bottom border-fuchsia">Thông tin đặt vé</h3>
                <div class="ticket-info">
                    <div class="movie-poster">
                        <img src="{{ \Storage::url($showtime->movie->image_path ?? '/images/default-poster.jpg') }}" alt="{{ $showtime->movie->name ?? 'Chưa xác định' }}" class="poster-image">
                    </div>
                    <div class="info-details">
                        <div class="info-line text-gray-light"><strong>Phim:</strong> <span id="movie-title">{{ $showtime->movie->name ?? 'Chưa xác định' }}</span></div>
                        <div class="info-line text-gray-light"><strong>Rạp:</strong> <span id="cinema-name">{{ $showtime->room->cinema->name ?? 'Chưa xác định' }}</span></div>
                        <div class="info-line text-gray-light"><strong>Suất:</strong> <span id="showtime-time">{{ $showtime->start_time->format('H:i d/m/Y') ?? 'Chưa xác định' }}</span></div>
                        <div class="info-line text-gray-light"><strong>Phòng:</strong> <span id="room-name">{{ $room['name'] ?? 'Chưa xác định' }}</span></div>
                    </div>
                </div>
                <div class="info-line text-gray-light"><strong>Ghế:</strong> <span id="selected-seats-summary">Chưa chọn ghế</span></div>
                <div class="info-line text-gray-light"><strong>Tiền vé:</strong> <span id="total-ticket-price-summary">0 ₫</span></div>

                <!-- Timer Section -->
                <div class="timer-section" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #333333;" id="timer-section" style="display: none;">
                    <h2 style="color: #ffffff; font-size: 1.2em; margin-bottom: 10px;">Time Remaining</h2>
                    <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;" id="timer-display">
                        <span style="background: #e5006e; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;" id="hours">0</span>
                        <span style="background: #e5006e; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;" id="minutes">0</span>
                        <span style="background: #e5006e; color: #fff; padding: 8px 12px; border-radius: 4px; font-size: 1.2em; font-weight: 600;" id="seconds">0</span>
                    </div>
                    <p style="color: #d3d3d3; font-size: 0.9em;">Hours Minutes Seconds</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script>
        let selectedSeats = [];
        let timerStarted = false;
        let timerInterval;

        const sessionId = '{{ session()->getId() }}';
        const showtimeId = '{{ $showtime->id }}';

        async function fetchInitialSeatStatus() {
            try {
                const response = await fetch(`/api/seats/status/${showtimeId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data && data.seats) {
                    data.seats.forEach(seat => {
                        const seatElement = document.querySelector(`.seat[data-seat-id="${seat.seat_id}"]`);
                        if (!seatElement) return;

                        seatElement.classList.remove('available', 'reserved', 'locked', 'selected');

                        if (seat.status === 'reserved') {
                            seatElement.classList.add('reserved');
                            seatElement.style.backgroundColor = '#dc3545';
                            seatElement.style.opacity = '0.6';
                            seatElement.style.pointerEvents = 'none';
                        } else if (seat.status === 'locked') {
                            if (seat.locked_by !== sessionId) {
                                seatElement.classList.add('locked');
                                seatElement.style.backgroundColor = '#ffc107';
                                seatElement.style.opacity = '0.6';
                                seatElement.style.pointerEvents = 'none';
                            } else {
                                // Nếu ghế do phiên hiện tại giữ thì đánh dấu là selected
                                seatElement.classList.add('selected');
                                seatElement.style.backgroundColor = '#e5006e';
                                seatElement.style.opacity = '1';
                                seatElement.style.pointerEvents = 'auto';
                                selectedSeats.push({
                                    id: seat.seat_id.toString(),
                                    label: seat.label,
                                    type: seat.type,
                                    price: seat.price
                                });
                            }
                        } else {
                            seatElement.classList.add('available');
                            const originalColor = seatElement.getAttribute('data-original-color');
                            seatElement.style.backgroundColor = originalColor || '#28a745';
                            seatElement.style.opacity = '1';
                            seatElement.style.pointerEvents = 'auto';
                        }
                    });

                    // Cập nhật lại tổng kết và gửi dữ liệu ban đầu về parent window
                    updateSummary();
                    sendSeatsToParent();
                }
            } catch (error) {
                console.error('Error fetching initial seat status:', error);
            }
        }

        // Call fetch on page load
        document.addEventListener('DOMContentLoaded', fetchInitialSeatStatus);

        console.log('Pusher Config:', {
            key: '{{ $pusher_key }}',
            cluster: '{{ $pusher_cluster }}',
            sessionId: sessionId
        });

        const pusher = new Pusher('{{ $pusher_key }}', {
            cluster: '{{ $pusher_cluster }}',
            forceTLS: true,
            disableStats: true,
            enabledTransports: ['ws', 'xhr_streaming', 'xhr_polling']
        });

        pusher.connection.bind('connected', function() {
            console.log('Pusher connected successfully');
        });
        pusher.connection.bind('error', function(err) {
            console.error('Pusher connection error:', err);
        });

        const channel = pusher.subscribe('showtime.' + showtimeId);

        channel.bind('pusher:subscription_succeeded', function() {
            console.log('Subscribed to channel showtime.' + showtimeId);
        });
        channel.bind('pusher:subscription_error', function(status) {
            console.error('Subscription error:', status);
        });

        channel.bind('seat-status-updated', function(data) {
            console.log('Received seat-status-updated event:', data);
            console.log('Comparing locked_by:', data.locked_by, 'with sessionId:', sessionId);
            console.log('Seat ID:', data.seat_id, 'Is even:', parseInt(data.seat_id) % 2 === 0);

            const seatElement = document.querySelector(`.seat[data-seat-id="${data.seat_id}"]`);
            if (seatElement) {
                if (data.locked_by !== sessionId) {
                    console.log('Processing seat update for seat_id:', data.seat_id);
                    seatElement.classList.remove('available', 'reserved', 'selected');
                    seatElement.classList.add(data.status);
                    if (data.status === 'reserved') {
                        seatElement.style.backgroundColor = '#dc3545';
                        seatElement.style.opacity = '0.6';
                        selectedSeats = selectedSeats.filter(seat => seat.id !== data.seat_id.toString());
                        updateSummary();
                        sendSeatsToParent();
                    } else if (data.status === 'available') {
                        const originalColor = seatElement.getAttribute('data-original-color');
                        seatElement.style.backgroundColor = originalColor || '#28a745';
                        seatElement.style.opacity = '1';
                    }
                } else {
                    console.log('Ignoring seat update: seat is locked by current session', data.seat_id);
                }
            } else {
                console.warn('Seat element not found for seat_id:', data.seat_id);
            }
        });

        function selectSeat(element) {
            const seatId = element.getAttribute('data-seat-id');
            const label = element.getAttribute('data-label');
            const type = element.getAttribute('data-type');
            const price = parseFloat(element.getAttribute('data-price'));
            const originalColor = element.getAttribute('data-original-color');

            console.log('Selecting seat:', { seatId, label, type, price });

            if (element.classList.contains('reserved') || element.classList.contains('locked')) {
                console.log('Seat is reserved or locked:', seatId);
                return;
            }

            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                element.style.backgroundColor = originalColor || '#28a745';
                element.style.opacity = '1';
                selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
            } else {
                element.classList.add('selected');
                element.style.backgroundColor = '#e5006e';
                element.style.opacity = '1';
                selectedSeats.push({ id: seatId, label: label, type: type, price: price });

                console.log('Sending reserve request for seat:', seatId);
                fetch('{{ route('client.seats.reserve', ['showtimeId' => $showtime->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ seat_ids: [seatId] })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Reserve seat response:', data);
                    if (data.error) {
                        console.warn('Reserve seat failed:', data.error);
                        element.classList.remove('selected');
                        element.style.backgroundColor = originalColor || '#28a745';
                        element.style.opacity = '1';
                        selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
                    }
                    updateSummary();
                    sendSeatsToParent();
                })
                .catch(error => {
                    console.error('Error reserving seat:', error);
                    element.classList.remove('selected');
                    element.style.backgroundColor = originalColor || '#28a745';
                    element.style.opacity = '1';
                    selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
                    updateSummary();
                    sendSeatsToParent();
                });
            }

            // Start timer on first seat selection
            if (!timerStarted) {
                console.log('Starting timer...');
                startTimer();
                document.getElementById('timer-section').style.display = 'block';
                timerStarted = true;
            }

            updateSummary();
        }

        function updateSummary() {
            console.log('Updating summary with selectedSeats:', selectedSeats);
            const seatsSummary = document.getElementById('selected-seats-summary');
            const ticketPriceSummary = document.getElementById('total-ticket-price-summary');

            if (!seatsSummary || !ticketPriceSummary) {
                console.error('One or more DOM elements not found:', { seatsSummary, ticketPriceSummary });
                return;
            }

            seatsSummary.textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => seat.label).join(', ') : 'Chưa chọn ghế';
            const totalTicketPrice = selectedSeats.reduce((sum, seat) => sum + (seat.price || 0), 0);
            ticketPriceSummary.textContent = totalTicketPrice.toLocaleString('vi-VN') + ' ₫';

            console.log('Summary updated:', {
                seats: seatsSummary.textContent,
                ticketPrice: ticketPriceSummary.textContent
            });
        }

        // Hàm gửi dữ liệu ghế về parent window
        function sendSeatsToParent() {
            console.log('Sending seats to parent:', selectedSeats);
            if (window.parent && window.parent.receiveSeats) {
                try {
                    window.parent.receiveSeats(selectedSeats);
                    console.log('Seats sent to parent successfully');
                } catch (error) {
                    console.error('Error sending seats to parent:', error);
                }
            } else {
                console.warn('Parent window or receiveSeats function not available');
            }
        }

        // Timer Logic
        function startTimer() {
            const now = new Date(); // Use current time dynamically
            const showtime = new Date('{{ $showtime->start_time->toIso8601String() }}');
            const diffMs = showtime - now;
            let timerDuration;

            console.log('Current time:', now);
            console.log('Showtime:', showtime);
            console.log('Difference in ms:', diffMs);

            if (diffMs > 3600000) { // > 1 hour (3600000 ms)
                timerDuration = 60000; // 1 minute for testing
            } else if (diffMs < 1800000) { // < 30 minutes (1800000 ms)
                timerDuration = 30000; // 30 seconds for testing
            } else {
                timerDuration = 60000; // Default 1 minute for testing
            }

            console.log('Timer duration set to:', timerDuration, 'ms');

            const endTime = new Date(now.getTime() + timerDuration);
            const timerDisplay = document.getElementById('timer-display');
            const hoursElem = document.getElementById('hours');
            const minutesElem = document.getElementById('minutes');
            const secondsElem = document.getElementById('seconds');

            function updateTimer() {
                const now = new Date();
                const diff = endTime - now;
                if (diff <= 0) {
                    clearInterval(timerInterval);
                    timerDisplay.innerHTML = `
                        <span style="background: #dc3545; color: #fff; padding: 10px 15px; border-radius: 4px; font-size: 1.2em; font-weight: 600;">Đơn hàng đã quá hạn</span>
                    `;
                    setTimeout(() => {
                        window.top.location.href = '{{ route('movies.show', ['id' => $showtime->movie_id ?? $movie->id]) }}';
                    }, 2000); // Redirect after 2 seconds
                    selectedSeats = []; // Reset selected seats
                    updateSummary();
                    sendSeatsToParent(); // Gửi trạng thái reset về parent
                    return;
                }

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                hoursElem.textContent = String(hours).padStart(2, '0');
                minutesElem.textContent = String(minutes).padStart(2, '0');
                secondsElem.textContent = String(seconds).padStart(2, '0');
            }

            updateTimer(); // Run immediately
            timerInterval = setInterval(updateTimer, 1000); // Start interval
            console.log('Timer started with interval:', timerInterval);
        }
    </script>
@else
    <div class="alert alert-danger bg-danger text-white">
        Không thể hiển thị sơ đồ ghế. Lý do:
        @if (!isset($showtime) || !$showtime)
            Suất chiếu không hợp lệ (ID: {{ $showtimeId ?? 'N/A' }}).
        @elseif (!isset($room) || !isset($room['rows']) || !isset($room['cols']) || $room['rows'] == 0 || $room['cols'] == 0)
            Phòng chiếu không có dữ liệu ghế hợp lệ.
        @endif
        <br><a href="{{ route('client.movies.ticketBooking', ['id' => $showtime->movie_id ?? $movie->id]) }}" class="btn btn-fuchsia mt-2">Quay lại chọn suất chiếu</a>
    </div>
@endif

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
    font-size: 0.95em;
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
.bg-danger { background-color: #dc3545; }
.text-white { color: #ffffff; }
.text-gray-light { color: #d3d3d3; }
.border-white { border-color: #ffffff; }
.border-fuchsia { border-color: #e5006e; }
.border-light { border-color: #d3d3d3; }
.form-select { appearance: none; }
.shadow-lg { box-shadow: 0 4px 12px rgba(0,0,0,0.5); }
</style>