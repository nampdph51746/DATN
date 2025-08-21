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
                                        @php
                                            $isLockedByCurrentSession = $seat['status'] === 'locked' && $seat['locked_by'] === session()->getId();
                                            $displayStatus = $isLockedByCurrentSession ? 'selected' : $seat['status'];
                                            $backgroundColor = $seat['status'] === 'maintenance' ? '#6c757d' : 
                                                              ($seat['status'] === 'reserved' ? '#dc3545' : 
                                                              ($seat['status'] === 'locked' ? ($isLockedByCurrentSession ? '#e5006e' : '#ffc107') : 
                                                              $seat['color_code']));
                                            $isClickable = !in_array($seat['status'], ['maintenance', 'reserved']) && 
                                                          !($seat['status'] === 'locked' && !$isLockedByCurrentSession);
                                        @endphp
                                        <div class="seat {{ $displayStatus }} {{ strtolower($seat['seat_type']) }}"
                                            data-seat-id="{{ $seat['seat_id'] }}"
                                            data-label="{{ $seat['label'] }}"
                                            data-type="{{ $seat['seat_type'] }}"
                                            data-price="{{ $showtime->base_price * ($seat['price'] ?? 1) }}"
                                            data-original-color="{{ $seat['color_code'] }}"
                                            data-locked-by="{{ $seat['locked_by'] ?? '' }}"
                                            style="background-color: {{ $backgroundColor }}; {{ !$isClickable ? 'opacity: 0.6; pointer-events: none;' : '' }}"
                                            @if ($isClickable)
                                                onclick="selectSeat(this)"
                                            @endif>
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
                            {{ $type->name }} ({{ number_format($showtime->base_price * ($type->price_modifier ?? 1), 0, ',', '.') }} ₫)
                        </div>
                    @endforeach
                    <div class="legend-item text-gray-light">
                        <span class="legend-color selected bg-fuchsia"></span> Đã chọn
                    </div>
                    <div class="legend-item text-gray-light">
                        <span class="legend-color reserved bg-danger"></span> Đã đặt
                    </div>
                    <div class="legend-item text-gray-light">
                        <span class="legend-color maintenance" style="background-color: #6c757d;"></span> Đang bảo trì
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
                <div class="timer-section" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #333333;" id="timer-section">
                    <div style="text-align: center;">
                        <h2 style="color: #ffffff; font-size: 1.4em; margin-bottom: 16px; font-weight: bold;">Time Remaining</h2>

                        <div style="display: flex; justify-content: center; gap: 16px; margin-bottom: 10px;" id="timer-display">
                            <!-- Giờ -->
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <span id="hours-seat"
                                    style="background: #e5006e; color: #fff; padding: 10px 10px; border-radius: 6px; font-size: 1.6em; font-weight: bold; min-width: 45px; text-align: center;">
                                    00
                                </span>
                                <span style="margin-top: 6px; font-size: 0.95em; color: #ffffff;">Giờ</span>
                            </div>

                            <!-- Phút -->
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <span id="minutes-seat"
                                    style="background: #e5006e; color: #fff; padding: 10px 10px; border-radius: 6px; font-size: 1.6em; font-weight: bold; min-width: 45px; text-align: center;">
                                    09
                                </span>
                                <span style="margin-top: 6px; font-size: 0.95em; color: #ffffff;">Phút</span>
                            </div>

                            <!-- Giây -->
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <span id="seconds-seat"
                                    style="background: #e5006e; color: #fff; padding: 10px 10px; border-radius: 6px; font-size: 1.6em; font-weight: bold; min-width: 45px; text-align: center;">
                                    19
                                </span>
                                <span style="margin-top: 6px; font-size: 0.95em; color: #ffffff;">Giây</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script>
        let selectedSeats = [];
        let timerStarted = false;
        let timerInterval;
        let countdownEndTime = null;
        let cleanupDisabled = false; // Flag để disable cleanup khi đang checkout

        const sessionId = '{{ session()->getId() }}';
        const showtimeId = '{{ $showtime->id }}';
        
        // Cấu hình giới hạn số ghế
        const MAX_SEATS_PER_BOOKING = {{ config('booking.max_seats_per_booking', 8) }}; // Tối đa {{ config('booking.max_seats_per_booking', 8) }} ghế mỗi lần đặt

        // Lắng nghe message từ parent frame
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'DISABLE_CLEANUP') {
                cleanupDisabled = true;
                console.log('Cleanup disabled due to checkout process');
            }
        });

        function showSeatLimitAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Vượt quá giới hạn',
                text: `Bạn chỉ có thể chọn tối đa ${MAX_SEATS_PER_BOOKING} ghế trong 1 lần đặt vé`,
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#e5006e'
            });
        }

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

                        seatElement.classList.remove('available', 'reserved', 'locked', 'selected', 'maintenance');

                        if (seat.status === 'reserved') {
                            seatElement.classList.add('reserved');
                            seatElement.style.backgroundColor = '#dc3545';
                            seatElement.style.opacity = '0.6';
                            seatElement.style.pointerEvents = 'none';
                            seatElement.removeAttribute('onclick');
                        } else if (seat.status === 'locked') {
                            if (seat.locked_by !== sessionId) {
                                // Ghế bị lock bởi session khác
                                seatElement.classList.add('locked');
                                seatElement.style.backgroundColor = '#ffc107';
                                seatElement.style.opacity = '0.6';
                                seatElement.style.pointerEvents = 'none';
                                seatElement.removeAttribute('onclick');
                            } else {
                                // Ghế được lock bởi session hiện tại - giữ nguyên selected state
                                if (!seatElement.classList.contains('selected')) {
                                    seatElement.classList.add('selected');
                                    seatElement.style.backgroundColor = '#e5006e';
                                    seatElement.style.opacity = '1';
                                    seatElement.style.pointerEvents = 'auto';
                                    seatElement.setAttribute('onclick', 'selectSeat(this)');
                                    
                                    // Thêm vào selectedSeats nếu chưa có
                                    if (!selectedSeats.some(s => s.id === seat.seat_id.toString())) {
                                        selectedSeats.push({
                                            id: seat.seat_id.toString(),
                                            label: seat.label,
                                            type: seat.type,
                                            price: seat.price
                                        });
                                    }
                                }
                            }
                        } else if (seat.status === 'maintenance') {
                            seatElement.classList.add('maintenance');
                            seatElement.style.backgroundColor = '#6c757d';
                            seatElement.style.opacity = '0.6';
                            seatElement.style.pointerEvents = 'none';
                            seatElement.removeAttribute('onclick');
                        } else {
                            seatElement.classList.add('available');
                            const originalColor = seatElement.getAttribute('data-original-color');
                            seatElement.style.backgroundColor = originalColor || '#28a745';
                            seatElement.style.opacity = '1';
                            seatElement.style.pointerEvents = 'auto';
                            seatElement.setAttribute('onclick', 'selectSeat(this)');
                        }
                    });

                    updateSummary();
                    sendSeatsToParent();
                }
            } catch (error) {
                console.error('Error fetching initial seat status:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Thêm các ghế đã selected (locked bởi session hiện tại) vào selectedSeats
            document.querySelectorAll('.seat.selected').forEach(seatElement => {
                const seatId = seatElement.getAttribute('data-seat-id');
                const label = seatElement.getAttribute('data-label');
                const type = seatElement.getAttribute('data-type');
                const price = parseFloat(seatElement.getAttribute('data-price'));
                
                selectedSeats.push({
                    id: seatId,
                    label: label,
                    type: type,
                    price: price
                });
            });
            
            fetchInitialSeatStatus();
            startTimer(); // Start timer immediately when page loads
            timerStarted = true;
            document.getElementById('timer-section').style.display = 'block';
            sendTimerToParent();
            
            // Update summary với ghế đã selected
            updateSummary();
            sendSeatsToParent();
        });

        // Cleanup khi user thoát trang
        async function cleanupSeats() {
            // Kiểm tra nếu cleanup bị disable (đang trong quá trình checkout)
            if (cleanupDisabled) {
                console.log('Cleanup skipped - checkout process in progress');
                return;
            }
            
            // Kiểm tra nếu parent đang trong process checkout
            try {
                if (window.parent && window.parent.document) {
                    const checkoutForm = window.parent.document.getElementById('checkoutForm');
                    if (checkoutForm && checkoutForm.classList.contains('submitting')) {
                        console.log('Cleanup skipped - form is being submitted');
                        return;
                    }
                }
            } catch (error) {
                // Cross-origin restrictions, ignore
            }
            
            if (selectedSeats.length > 0) {
                try {
                    console.log('Cleaning up seats before page unload:', selectedSeats.map(s => s.id));
                    
                    // Gọi API release tất cả ghế của session
                    await fetch(`/api/seats/release-all/${showtimeId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        keepalive: true // Đảm bảo request được gửi ngay cả khi trang đang đóng
                    });
                    
                    console.log('All seats released successfully');
                } catch (error) {
                    console.error('Error releasing seats on page unload:', error);
                }
            }
        }

        // Event listeners cho cleanup - sử dụng nhiều event để đảm bảo
        window.addEventListener('beforeunload', cleanupSeats);
        window.addEventListener('unload', cleanupSeats);
        window.addEventListener('pagehide', cleanupSeats);

        // Cleanup khi user chuyển tab hoặc minimize window (sau 30 giây không focus)
        let unfocusTimeout;
        let isPageVisible = true;
        
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && isPageVisible) {
                isPageVisible = false;
                console.log('Page became hidden, starting unfocus timeout');
                
                // Sau 30 giây không focus thì release ghế
                unfocusTimeout = setTimeout(() => {
                    console.log('Page unfocused for 30 seconds, releasing seats');
                    cleanupSeats();
                }, 30000);
                
            } else if (!document.hidden) {
                isPageVisible = true;
                if (unfocusTimeout) {
                    console.log('Page became visible again, clearing unfocus timeout');
                    clearTimeout(unfocusTimeout);
                    unfocusTimeout = null;
                }
            }
        });

        // Cleanup khi window lose focus (chuyển app khác)
        let windowUnfocusTimeout;
        
        window.addEventListener('blur', function() {
            console.log('Window lost focus, starting unfocus timeout');
            windowUnfocusTimeout = setTimeout(() => {
                console.log('Window unfocused for 30 seconds, releasing seats');
                cleanupSeats();
            }, 30000);
        });

        window.addEventListener('focus', function() {
            if (windowUnfocusTimeout) {
                console.log('Window gained focus, clearing unfocus timeout');
                clearTimeout(windowUnfocusTimeout);
                windowUnfocusTimeout = null;
            }
        });

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

            const seatElement = document.querySelector(`.seat[data-seat-id="${data.seat_id}"]`);
            if (seatElement) {
                console.log('Current seat element classes before update:', seatElement.className);
                console.log('Current seat onclick before update:', seatElement.getAttribute('onclick'));
                
                if (data.locked_by !== sessionId) {
                    console.log('Processing seat update for seat_id:', data.seat_id, 'status:', data.status);
                    seatElement.classList.remove('available', 'reserved', 'selected', 'maintenance', 'locked');
                    seatElement.classList.add(data.status);
                    if (data.status === 'reserved') {
                        seatElement.style.backgroundColor = '#dc3545';
                        seatElement.style.opacity = '0.6';
                        seatElement.style.pointerEvents = 'none';
                        seatElement.removeAttribute('onclick');
                        selectedSeats = selectedSeats.filter(seat => seat.id !== data.seat_id.toString());
                        updateSummary();
                        sendSeatsToParent();
                    } else if (data.status === 'locked') {
                        // Ghế bị lock bởi session khác
                        seatElement.style.backgroundColor = '#ffc107';
                        seatElement.style.opacity = '0.6';
                        seatElement.style.pointerEvents = 'none';
                        seatElement.removeAttribute('onclick');
                    } else if (data.status === 'maintenance') {
                        seatElement.style.backgroundColor = '#6c757d';
                        seatElement.style.opacity = '0.6';
                        seatElement.style.pointerEvents = 'none';
                        seatElement.removeAttribute('onclick');
                    } else if (data.status === 'available') {
                        const originalColor = seatElement.getAttribute('data-original-color');
                        seatElement.style.backgroundColor = originalColor || '#28a745';
                        seatElement.style.opacity = '1';
                        seatElement.style.pointerEvents = 'auto';
                        
                        // Thêm lại onclick event để có thể click
                        seatElement.setAttribute('onclick', 'selectSeat(this)');
                    }
                    
                    console.log('Seat element classes after update:', seatElement.className);
                    console.log('Seat onclick after update:', seatElement.getAttribute('onclick'));
                } else {
                    console.log('Ignoring seat update: seat is locked by current session', data.seat_id);
                }
            } else {
                console.warn('Seat element not found for seat_id:', data.seat_id);
            }
        });

        // Listener cho event seat-released
        channel.bind('seat-released', function(data) {
            console.log('Received seat-released event:', data);
            
            const seatElement = document.querySelector(`.seat[data-seat-id="${data.seat_id}"]`);
            if (seatElement) {
                // Kiểm tra nếu không phải session hiện tại release thì cập nhật UI
                if (data.released_by !== sessionId) {
                    console.log('Processing seat release for seat_id:', data.seat_id);
                    
                    // Reset ghế về trạng thái available
                    seatElement.classList.remove('available', 'reserved', 'selected', 'maintenance', 'locked');
                    seatElement.classList.add('available');
                    
                    const originalColor = seatElement.getAttribute('data-original-color');
                    seatElement.style.backgroundColor = originalColor || '#28a745';
                    seatElement.style.opacity = '1';
                    seatElement.style.pointerEvents = 'auto';
                    seatElement.setAttribute('onclick', 'selectSeat(this)');
                    
                    console.log('Seat', data.seat_id, 'released and made available');
                } else {
                    console.log('Ignoring seat release: released by current session', data.seat_id);
                }
            } else {
                console.warn('Seat element not found for released seat_id:', data.seat_id);
            }
        });

        async function selectSeat(element) {
            const seatId = element.getAttribute('data-seat-id');
            const label = element.getAttribute('data-label');
            const type = element.getAttribute('data-type');
            const price = parseFloat(element.getAttribute('data-price'));
            const originalColor = element.getAttribute('data-original-color');

            console.log('Selecting seat:', { seatId, label, type, price });

            // Kiểm tra ghế maintenance hoặc reserved (đã confirm)
            if (element.classList.contains('maintenance') || element.classList.contains('reserved')) {
                console.log('Seat is under maintenance or reserved:', seatId);
                return;
            }

            // Kiểm tra ghế locked bởi session khác (không phải selected của session hiện tại)
            if (element.classList.contains('locked') && !element.classList.contains('selected')) {
                console.log('Seat is locked by another session:', seatId);
                return;
            }

            // Kiểm tra nếu là ghế đôi
            const isCoupleseat = ['Sweetbox', 'Couple Sofa', 'Couple Bed'].includes(type);
            
            if (isCoupleseat) {
                // Xử lý ghế đôi
                await selectCoupleSeat(element);
            } else {
                // Xử lý ghế đơn
                await selectSingleSeat(element);
            }
        }

        async function selectSingleSeat(element) {
            const seatId = element.getAttribute('data-seat-id');
            const label = element.getAttribute('data-label');
            const type = element.getAttribute('data-type');
            const price = parseFloat(element.getAttribute('data-price'));
            const originalColor = element.getAttribute('data-original-color');

            if (element.classList.contains('selected')) {
                // Bỏ chọn ghế
                element.classList.remove('selected');
                element.style.backgroundColor = originalColor || '#28a745';
                element.style.opacity = '1';
                selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
                
                // Gọi API để release ghế và broadcast real-time
                await releaseSingleSeat(seatId);
            } else {
                // Kiểm tra giới hạn số ghế trước khi chọn
                if (selectedSeats.length >= MAX_SEATS_PER_BOOKING) {
                    showSeatLimitAlert();
                    return;
                }
                
                // Chọn ghế
                element.classList.add('selected');
                element.style.backgroundColor = '#e5006e';
                element.style.opacity = '1';
                selectedSeats.push({ id: seatId, label: label, type: type, price: price });
                
                // Gọi API để reserve ghế và broadcast real-time
                await updateSeatReservation();
            }
        }

        async function selectCoupleSeat(element) {
            const seatId = element.getAttribute('data-seat-id');
            const label = element.getAttribute('data-label');
            const type = element.getAttribute('data-type');
            const price = parseFloat(element.getAttribute('data-price'));
            const originalColor = element.getAttribute('data-original-color');

            // Tìm ghế đối tác (ghế liền kề)
            const partnerSeat = findCouplePartner(element);
            
            if (!partnerSeat) {
                console.log('Partner seat not found for couple seat:', seatId);
                await selectSingleSeat(element);
                return;
            }

            // Kiểm tra ghế đối tác có available không
            if (partnerSeat.classList.contains('reserved') || partnerSeat.classList.contains('locked') || partnerSeat.classList.contains('maintenance')) {
                console.log('Partner seat is not available:', partnerSeat.getAttribute('data-seat-id'));
                return;
            }

            const partnerSeatId = partnerSeat.getAttribute('data-seat-id');
            const partnerLabel = partnerSeat.getAttribute('data-label');
            const partnerType = partnerSeat.getAttribute('data-type');
            const partnerPrice = parseFloat(partnerSeat.getAttribute('data-price'));
            const partnerOriginalColor = partnerSeat.getAttribute('data-original-color');

            // Kiểm tra trạng thái hiện tại của cả 2 ghế
            const isCurrentSelected = element.classList.contains('selected');
            const isPartnerSelected = partnerSeat.classList.contains('selected');

            if (isCurrentSelected && isPartnerSelected) {
                // Bỏ chọn cả 2 ghế
                element.classList.remove('selected');
                element.style.backgroundColor = originalColor || '#28a745';
                element.style.opacity = '1';
                
                partnerSeat.classList.remove('selected');
                partnerSeat.style.backgroundColor = partnerOriginalColor || '#28a745';
                partnerSeat.style.opacity = '1';
                
                selectedSeats = selectedSeats.filter(seat => seat.id !== seatId && seat.id !== partnerSeatId);
                
                // Gọi API để release cả 2 ghế
                await releaseCoupleSeat([seatId, partnerSeatId]);
            } else {
                // Kiểm tra giới hạn số ghế trước khi chọn cả 2 ghế
                if (selectedSeats.length + 2 > MAX_SEATS_PER_BOOKING) {
                    showSeatLimitAlert();
                    return;
                }
                
                // Chọn cả 2 ghế
                element.classList.add('selected');
                element.style.backgroundColor = '#e5006e';
                element.style.opacity = '1';
                
                partnerSeat.classList.add('selected');
                partnerSeat.style.backgroundColor = '#e5006e';
                partnerSeat.style.opacity = '1';
                
                // Thêm vào danh sách nếu chưa có
                if (!selectedSeats.some(seat => seat.id === seatId)) {
                    selectedSeats.push({ id: seatId, label: label, type: type, price: price });
                }
                if (!selectedSeats.some(seat => seat.id === partnerSeatId)) {
                    selectedSeats.push({ id: partnerSeatId, label: partnerLabel, type: partnerType, price: partnerPrice });
                }
                
                // Gọi API để reserve cả 2 ghế
                await updateSeatReservation();
            }
        }

        function findCouplePartner(seatElement) {
            const label = seatElement.getAttribute('data-label');
            const [row, seatNumber] = label.split('-');
            const currentSeatNum = parseInt(seatNumber);
            
            // Logic ghế đôi: số lẻ ghép với số chẵn kế tiếp, số chẵn ghép với số lẻ trước đó
            let partnerSeatNum;
            if (currentSeatNum % 2 === 1) {
                // Ghế lẻ -> tìm ghế chẵn kế tiếp
                partnerSeatNum = currentSeatNum + 1;
            } else {
                // Ghế chẵn -> tìm ghế lẻ trước đó
                partnerSeatNum = currentSeatNum - 1;
            }
            
            const partnerLabel = row + '-' + String(partnerSeatNum).padStart(2, '0');
            return document.querySelector(`.seat[data-label="${partnerLabel}"]`);
        }

        async function updateSeatReservation() {
            // Gửi tất cả seat_ids được chọn
            const seatIds = selectedSeats.map(seat => seat.id);
            
            // Chỉ gọi API nếu có ghế được chọn
            if (seatIds.length === 0) {
                console.log('No seats selected, skipping reservation');
                updateSummary();
                sendSeatsToParent();
                return;
            }
            
            console.log('Sending reserve request for seats:', seatIds);
            try {
                const response = await fetch('{{ route('client.seats.reserve', ['showtimeId' => $showtime->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ seat_ids: seatIds })
                });
                const data = await response.json();
                console.log('Reserve seat response:', data);
                if (data.error) {
                    console.warn('Reserve seat failed:', data.error);
                    // Revert UI và selectedSeats nếu có lỗi
                    selectedSeats = selectedSeats.filter(seat => !seatIds.includes(seat.id));
                    document.querySelectorAll('.seat.selected').forEach(seatEl => {
                        const id = seatEl.getAttribute('data-seat-id');
                        if (!selectedSeats.some(seat => seat.id === id)) {
                            seatEl.classList.remove('selected');
                            seatEl.style.backgroundColor = seatEl.getAttribute('data-original-color') || '#28a745';
                            seatEl.style.opacity = '1';
                        }
                    });
                }
            } catch (error) {
                console.error('Error reserving seats:', error);
                // Revert UI và selectedSeats nếu có lỗi
                selectedSeats = selectedSeats.filter(seat => !seatIds.includes(seat.id));
                document.querySelectorAll('.seat.selected').forEach(seatEl => {
                    const id = seatEl.getAttribute('data-seat-id');
                    if (!selectedSeats.some(seat => seat.id === id)) {
                        seatEl.classList.remove('selected');
                        seatEl.style.backgroundColor = seatEl.getAttribute('data-original-color') || '#28a745';
                        seatEl.style.opacity = '1';
                    }
                });
            }

            updateSummary();
            sendSeatsToParent();
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

        async function releaseSingleSeat(seatId) {
            try {
                console.log('Releasing single seat:', seatId);
                const response = await fetch(`/api/seats/release/${showtimeId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ seat_ids: [seatId] })
                });
                
                const data = await response.json();
                console.log('Release single seat response:', data);
                
                if (data.error) {
                    console.warn('Release single seat failed:', data.error);
                    // Nếu có lỗi, revert lại UI
                    const seatElement = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
                    if (seatElement) {
                        seatElement.classList.add('selected');
                        seatElement.style.backgroundColor = '#e5006e';
                        // Thêm lại ghế vào selectedSeats nếu chưa có
                        if (!selectedSeats.some(seat => seat.id === seatId)) {
                            const label = seatElement.getAttribute('data-label');
                            const type = seatElement.getAttribute('data-type');
                            const price = parseFloat(seatElement.getAttribute('data-price'));
                            selectedSeats.push({ id: seatId, label: label, type: type, price: price });
                        }
                    }
                }
            } catch (error) {
                console.error('Error releasing single seat:', error);
                // Nếu có lỗi network, revert lại UI
                const seatElement = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
                if (seatElement) {
                    seatElement.classList.add('selected');
                    seatElement.style.backgroundColor = '#e5006e';
                    // Thêm lại ghế vào selectedSeats nếu chưa có
                    if (!selectedSeats.some(seat => seat.id === seatId)) {
                        const label = seatElement.getAttribute('data-label');
                        const type = seatElement.getAttribute('data-type');
                        const price = parseFloat(seatElement.getAttribute('data-price'));
                        selectedSeats.push({ id: seatId, label: label, type: type, price: price });
                    }
                }
            }
            
            console.log('selectedSeats after release:', selectedSeats);
            updateSummary();
            sendSeatsToParent();
        }

        async function releaseCoupleSeat(seatIds) {
            try {
                console.log('Releasing couple seats:', seatIds);
                const response = await fetch(`/api/seats/release/${showtimeId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ seat_ids: seatIds })
                });
                
                const data = await response.json();
                console.log('Release couple seats response:', data);
                
                if (data.error) {
                    console.warn('Release couple seats failed:', data.error);
                    // Nếu có lỗi, revert lại UI cho cả 2 ghế
                    seatIds.forEach(seatId => {
                        const seatElement = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
                        if (seatElement) {
                            seatElement.classList.add('selected');
                            seatElement.style.backgroundColor = '#e5006e';
                            // Thêm lại ghế vào selectedSeats nếu chưa có
                            if (!selectedSeats.some(seat => seat.id === seatId)) {
                                const label = seatElement.getAttribute('data-label');
                                const type = seatElement.getAttribute('data-type');
                                const price = parseFloat(seatElement.getAttribute('data-price'));
                                selectedSeats.push({ id: seatId, label: label, type: type, price: price });
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error releasing couple seats:', error);
                // Nếu có lỗi network, revert lại UI cho cả 2 ghế
                seatIds.forEach(seatId => {
                    const seatElement = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
                    if (seatElement) {
                        seatElement.classList.add('selected');
                        seatElement.style.backgroundColor = '#e5006e';
                        // Thêm lại ghế vào selectedSeats nếu chưa có
                        if (!selectedSeats.some(seat => seat.id === seatId)) {
                            const label = seatElement.getAttribute('data-label');
                            const type = seatElement.getAttribute('data-type');
                            const price = parseFloat(seatElement.getAttribute('data-price'));
                            selectedSeats.push({ id: seatId, label: label, type: type, price: price });
                        }
                    }
                });
            }
            
            updateSummary();
            sendSeatsToParent();
        }

        function sendSeatsToParent() {
            console.log('Sending seats to parent:', selectedSeats);
            const cinemaName = document.getElementById('cinema-name')?.textContent || 'Chưa xác định';
            if (window.parent && window.parent.receiveSeats) {
                try {
                    window.parent.receiveSeats({
                        seats: selectedSeats,
                        cinemaName: cinemaName
                    });
                    console.log('Seats and cinemaName sent to parent successfully:', { seats: selectedSeats, cinemaName });
                } catch (error) {
                    console.error('Error sending seats and cinemaName to parent:', error);
                }
            } else {
                console.warn('Parent window or receiveSeats function not available');
            }
        }

        // Đảm bảo timer đồng bộ khi chuyển bước
window.getTimerEndTime = function() {
    // Trả về thời gian kết thúc timer (timestamp ms)
    return countdownEndTime ? countdownEndTime.getTime() : null;
};

        // Khi gửi timer sang parent, luôn gửi giá trị mới nhất
        function sendTimerToParent() {
            if (window.parent && window.parent.receiveTimer) {
                try {
                    window.parent.receiveTimer({
                        endTime: window.getTimerEndTime()
                    });
                    console.log('Timer endTime sent to parent:', window.getTimerEndTime());
                } catch (error) {
                    console.error('Error sending timer to parent:', error);
                }
            } else {
                console.warn('Parent window or receiveTimer function not available');
            }
        }

        function startTimer() {
            // Tính khoảng cách thời gian giữa hiện tại và suất chiếu
            const showtimeStart = new Date("{{ $showtime->start_time->format('Y-m-d H:i:s') }}".replace(/-/g, '/'));
            const now = new Date();
            const diffMinutes = (showtimeStart - now) / (1000 * 60);

            // Nếu nhỏ hơn hoặc bằng 15 phút thì không cho đặt nữa, hiển thị thông báo nhưng không chuyển trang
            if (diffMinutes <= 15) {
                const timerDisplay = document.getElementById('timer-display');
                if (timerDisplay) {
                    timerDisplay.innerHTML = `<span style=\"background: #dc3545; color: #fff; padding: 10px 15px; border-radius: 4px; font-size: 1.2em; font-weight: 600;\">Không thể đặt vé khi suất chiếu sắp bắt đầu! Vui lòng chọn suất khác.</span>`;
                }
                // Disable tất cả ghế
                document.querySelectorAll('.seat').forEach(seat => {
                    seat.style.pointerEvents = 'none';
                    seat.style.opacity = '0.5';
                });
                return;
            }

            // Nếu nhỏ hơn hoặc bằng 30 phút thì timer chỉ còn 5 phút
            let timerDuration = 600000; // 10 phút mặc định
            if (diffMinutes <= 30) {
                timerDuration = 300000; // 5 phút
            }
            const endTime = new Date(now.getTime() + timerDuration);
            countdownEndTime = endTime;

            const timerDisplay = document.getElementById('timer-display');
            const hoursElem = document.getElementById('hours-seat');
            const minutesElem = document.getElementById('minutes-seat');
            const secondsElem = document.getElementById('seconds-seat');

            function updateTimer() {
                const now = new Date();
                const diff = countdownEndTime - now;
                if (diff <= 0) {
                    clearInterval(timerInterval);
                    timerStarted = false;
                    countdownEndTime = null;
                    timerDisplay.innerHTML = `
                        <span style=\"background: #dc3545; color: #fff; padding: 10px 15px; border-radius: 4px; font-size: 1.2em; font-weight: 600;\">Đơn hàng đã quá hạn</span>
                    `;
                    selectedSeats = [];
                    updateSummary();
                    sendSeatsToParent();
                    sendTimerToParent();
                    window.top.location.href = '/'; // Chuyển về trang chủ
                    return;
                }

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                hoursElem.textContent = String(hours).padStart(2, '0');
                minutesElem.textContent = String(minutes).padStart(2, '0');
                secondsElem.textContent = String(seconds).padStart(2, '0');
            }

            updateTimer();
            timerInterval = setInterval(updateTimer, 1000);
            console.log('Timer started with interval:', timerInterval);
        }

        // Handle reset seats message from parent
        window.addEventListener('message', function(event) {
            if (event.data.resetSeats) {
                selectedSeats.forEach(seat => {
                    const seatElement = document.querySelector(`.seat[data-seat-id="${seat.id}"]`);
                    if (seatElement) {
                        seatElement.classList.remove('selected');
                        seatElement.style.backgroundColor = seatElement.getAttribute('data-original-color') || '#28a745';
                        seatElement.style.opacity = '1';
                    }
                });
                selectedSeats = [];
                updateSummary();
                sendSeatsToParent();
            }
        });
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

.seat.maintenance {
    cursor: not-allowed;
    opacity: 0.6;
    background-color: #6c757d !important;
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