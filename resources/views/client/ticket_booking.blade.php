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
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
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

    .snack-table th,
    .snack-table td {
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
    .bg-dark {
        background-color: #1c1c1c;
    }

    .bg-dark-gray {
        background-color: #222222;
    }

    .bg-fuchsia {
        background-color: #e5006e;
    }

    .bg-gradient-fuchsia {
        background: linear-gradient(to right, #e5006e, #1a1a1a);
    }

    .custom-btn {
        padding: 10px 24px;
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        border: none;
        border-radius: 9999px;
        /* Full rounded */
        transition: background-color 0.3s ease;
    }

    .bg-danger {
        background-color: #dc3545;
    }

    .text-white {
        color: #ffffff;
    }

    .text-gray-light {
        color: #d3d3d3;
    }

    .border-white {
        border-color: #ffffff;
    }

    .border-fuchsia {
        border-color: #e5006e;
    }

    .border-light {
        border-color: #d3d3d3;
    }

    .form-select {
        appearance: none;
    }

    .shadow-lg {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .auto-promotion-info {
        margin: 8px 0;
        padding: 8px 12px;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid #22c55e;
        border-radius: 6px;
        color: #22c55e;
        font-size: 0.9em;
        display: none;
    }

    .auto-promotion-info.show {
        display: block;
    }

    .auto-promotion-info strong {
        color: #16a34a;
    }

    .rank-promotion {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        border: 1px solid #f59e0b;
        color: #92400e;
    }

    .general-promotion {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid #22c55e;
        color: #22c55e;
    }

    .promotion-type-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75em;
        font-weight: 600;
        margin-left: 8px;
    }

    .rank-badge {
        background: #fbbf24;
        color: #92400e;
    }

    .general-badge {
        background: #22c55e;
        color: white;
    }

    /* Promotion suggestion styles */
    .promotion-suggestion-btn {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.85em;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid;
        text-align: center;
        margin: 4px;
        position: relative;
        min-width: 140px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Rank-specific promotion styles */
    .rank-kim-cuong-btn {
        background: linear-gradient(135deg, #e11d48, #be123c);
        color: white;
        border-color: #9f1239;
    }

    .rank-kim-cuong-btn:hover {
        background: linear-gradient(135deg, #be123c, #9f1239);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(225, 29, 72, 0.4);
    }

    .rank-vang-btn {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-color: #b45309;
    }

    .rank-vang-btn:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(245, 158, 11, 0.4);
    }

    .rank-bac-btn {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        border-color: #374151;
    }

    .rank-bac-btn:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(107, 114, 128, 0.4);
    }

    .rank-dong-btn {
        background: linear-gradient(135deg, #92400e, #78350f);
        color: white;
        border-color: #451a03;
    }

    .rank-dong-btn:hover {
        background: linear-gradient(135deg, #78350f, #451a03);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(146, 64, 14, 0.4);
    }

    .general-promotion-btn {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border-color: #15803d;
    }

    .general-promotion-btn:hover {
        background: linear-gradient(135deg, #16a34a, #15803d);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(34, 197, 94, 0.4);
    }

    .promotion-info {
        font-size: 0.75em;
        opacity: 0.9;
        margin-top: 3px;
        font-weight: 400;
    }

    .promotion-rank-icon {
        font-size: 1.1em;
        margin-right: 4px;
    }

    .promotion-discount-highlight {
        font-weight: 600;
        font-size: 0.9em;
    }

    /* Promotion sections */
    .promotion-section {
        margin-bottom: 15px;
        padding: 12px;
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
        border-left: 4px solid;
        position: relative;
    }

    .promotion-section.rank-section {
        border-left-color: #f59e0b;
    }

    .promotion-section.general-section {
        border-left-color: #22c55e;
    }

    .promotion-section-title {
        font-size: 0.9em;
        font-weight: 600;
        margin-bottom: 8px;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4px 0;
    }

    .promotion-section-title:hover {
        color: #e5006e;
    }

    .promotion-toggle-icon {
        transition: transform 0.3s ease;
        font-size: 1.2em;
    }

    .promotion-toggle-icon.collapsed {
        transform: rotate(-90deg);
    }

    .promotion-content {
        max-height: 200px;
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    .promotion-content.collapsed {
        max-height: 0;
        overflow: hidden;
    }

    .promotion-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 8px;
        margin-bottom: 10px;
    }

    .promotion-count-badge {
        background: rgba(229, 0, 110, 0.8);
        color: white;
        font-size: 0.7em;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 8px;
    }

    .promotion-show-more {
        text-align: center;
        padding: 8px;
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8em;
        color: #e5006e;
        transition: background 0.3s ease;
        margin-top: 8px;
    }

    .promotion-show-more:hover {
        background: rgba(229, 0, 110, 0.2);
    }

    /* Compact promotion button */
    .promotion-suggestion-btn.compact {
        padding: 6px 10px;
        min-width: 120px;
        font-size: 0.8em;
    }

    .promotion-suggestion-btn.compact .promotion-info {
        font-size: 0.7em;
        margin-top: 2px;
    }

    /* Custom scrollbar for promotion content */
    .promotion-content::-webkit-scrollbar {
        width: 6px;
    }

    .promotion-content::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.1);
        border-radius: 3px;
    }

    .promotion-content::-webkit-scrollbar-thumb {
        background: #e5006e;
        border-radius: 3px;
    }

    .promotion-content::-webkit-scrollbar-thumb:hover {
        background: #c4005c;
    }

    /* Tìm kiếm mã giảm giá */
    .promotion-search-box {
        margin-bottom: 15px;
        position: relative;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05));
        border: 2px solid rgba(59, 130, 246, 0.3);
        border-radius: 12px;
        padding: 8px;
        transition: all 0.3s ease;
    }

    .promotion-search-box:hover {
        border-color: rgba(59, 130, 246, 0.5);
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(37, 99, 235, 0.08));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .promotion-search-input {
        width: 100%;
        padding: 12px 45px 12px 16px;
        border: none;
        border-radius: 8px;
        background: rgba(26, 26, 26, 0.8);
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        outline: none;
        backdrop-filter: blur(10px);
    }

    .promotion-search-input:focus {
        background: rgba(26, 26, 26, 0.95);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        transform: scale(1.02);
    }

    .promotion-search-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
        font-style: italic;
        font-weight: 400;
    }

    .promotion-search-icon {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #3b82f6;
        font-size: 16px;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .promotion-search-box:hover .promotion-search-icon {
        color: #2563eb;
        transform: translateY(-50%) scale(1.1);
    }

    /* Search results indicator */
    .promotion-search-results {
        position: absolute;
        top: -8px;
        right: -8px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 8px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .promotion-search-results.show {
        opacity: 1;
    }

    /* Loading states */
    .promotion-loading {
        text-align: center;
        padding: 20px;
        color: #666;
    }

    .promotion-skeleton {
        background: linear-gradient(90deg, rgba(255,255,255,0.1) 25%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.1) 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 6px;
        height: 60px;
        margin-bottom: 8px;
    }

    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Improved responsive grid */
    .promotion-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 8px;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .promotion-grid {
            grid-template-columns: 1fr;
            gap: 6px;
        }
        
        .promotion-suggestion-btn.compact {
            padding: 8px 12px;
            font-size: 0.85em;
        }
    }

    /* Empty state */
    .promotion-empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #666;
        font-style: italic;
    }

    .promotion-empty-state-icon {
        font-size: 2em;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    /* Improved promotion button hover effects */
    .promotion-suggestion-btn {
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .promotion-suggestion-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.5s ease;
    }

    .promotion-suggestion-btn:hover::before {
        left: 100%;
    }

    /* Stats display */
    .promotion-stats {
        font-size: 0.75em;
        color: #888;
        margin-top: 5px;
        text-align: center;
    }

    .user-rank-display {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8em;
        font-weight: 600;
        margin-left: 8px;
    }

    .user-rank-kim-cuong {
        background: #e11d48;
        color: white;
    }

    .user-rank-vang {
        background: #f59e0b;
        color: white;
    }

    .user-rank-bac {
        background: #6b7280;
        color: white;
    }

    /* Promotion input styling */
    .promotion-input-container {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .promotion-code-input {
        flex: 1;
        padding: 12px 16px;
        border: 2px solid #444;
        border-radius: 10px;
        background: #1a1a1a;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        outline: none;
    }

    .promotion-code-input:focus {
        border-color: #e5006e;
        background: #222;
        box-shadow: 0 0 12px rgba(229, 0, 110, 0.3);
        transform: translateY(-1px);
    }

    .promotion-code-input:hover {
        border-color: #666;
        background: #1f1f1f;
    }

    .promotion-code-input.applied {
        background: linear-gradient(135deg, #1a4a37, #15542a);
        border-color: #22c55e;
        color: #fff;
        font-weight: 600;
    }

    .promotion-code-input.applied:focus {
        border-color: #16a34a;
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.4);
    }

    .promotion-code-input::placeholder {
        color: #888;
        font-weight: 400;
        font-style: italic;
    }

    .promotion-code-input.applied::placeholder {
        color: #a7f3d0;
    }

    /* Button styling improvements */
    .promotion-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .promotion-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.5s ease;
    }

    .promotion-btn:hover::before {
        left: 100%;
    }

    .promotion-apply-btn {
        background: linear-gradient(135deg, #e5006e, #c4005c);
        color: white;
        box-shadow: 0 4px 12px rgba(229, 0, 110, 0.3);
    }

    .promotion-apply-btn:hover {
        background: linear-gradient(135deg, #c4005c, #a3004a);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(229, 0, 110, 0.4);
    }

    .promotion-reset-btn {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    .promotion-reset-btn:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(107, 114, 128, 0.4);
    }

    /* Promotion feedback styling */
    .promotion-feedback {
        margin-top: 8px;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .promotion-feedback.success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .promotion-feedback.error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .promotion-feedback.info {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #3b82f6;
    }

    .promotion-feedback.warning {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #f59e0b;
    }
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
                                <h3>{{ $movie->title }}</h3>
                                @if (empty($dates))
                                    <p>Không có suất chiếu nào cho phim này. Vui lòng kiểm tra lại dữ liệu hoặc chọn phim
                                        khác.</p>
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
                                <iframe id="seat-map-iframe" src="" width="100%" height="700"
                                    style="border: none; overflow: hidden; display: none;" onload="onIframeLoad()"
                                    onerror="onIframeError()"></iframe>
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
                                <div class="basis-[70%] bg-[#121212] rounded-lg shadow-md p-4">
                                    <h2 class="text-lg text-white font-semibold mb-3 border-b border-fuchsia pb-1">Snack
                                        Selection</h2>
                                    <div class="grid grid-cols-[repeat(auto-fit,minmax(220px,1fr))] gap-4">
                                        @foreach ($products as $product)
                                            <div class="flex flex-col items-center bg-white p-3 rounded-lg text-center">
                                                <img src="{{ asset('storage/' . $product->image_url) }}"
                                                    alt="{{ $product->name }}"
                                                    class="mb-2 w-24 h-24 object-cover rounded" />
                                                <div class="snack-info">
                                                    <h4 class="text-base">{{ $product->name }}</h4>
                                                    @if ($product->productVariants->isNotEmpty())
                                                        <select
                                                            class="variant-select form-select mt-2 p-1.5 rounded-md border text-sm"
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
                                                    <input type="number" id="quantity-{{ $product->id }}" value="0"
                                                        min="0"
                                                        class="w-12 text-center border border-gray-300 rounded-md p-1 text-sm"
                                                        readonly />
                                                    <button onclick="updateQuantity('{{ $product->id }}', 1)"
                                                        class="p-1.5 bg-red-500 text-white border-none rounded-md text-sm">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="basis-[30%] summary-section">
                                    <h3 class="summary-title">Thông tin đặt vé</h3>
                                    <div class="ticket-info">
                                        <div class="movie-poster">
                                            <img src="{{ $movie->image_path ? asset('storage/' . $movie->image_path) : asset('images/default-poster.jpg') }}"
                                                alt="{{ $movie->title ?? 'Chưa xác định' }}" class="poster-image" />
                                        </div>
                                        <div class="info-details">
                                            <div class="info-line"><strong>Phim:</strong> <span
                                                    id="movie-title">{{ $movie->title ?? 'Chưa xác định' }}</span></div>
                                            <div class="info-line"><strong>Rạp:</strong> <span
                                                    id="summary-cinema-name">{{ $cinema->name ?? ($showtimeId ? 'Chưa chọn suất chiếu' : 'Chưa xác định') }}</span>
                                            </div>
                                            <div class="info-line"><strong>Suất:</strong> <span
                                                    id="selected-showtime">{{ $showtime?->start_time?->format('H:i d/m/Y') ?? ($showtimeId ? 'Chưa chọn suất chiếu' : 'Chưa xác định') }}</span>
                                            </div>
                                            <div class="info-line"><strong>Phòng:</strong> <span
                                                    id="room-name">{{ $room->name ?? ($showtimeId ? 'Chưa chọn suất chiếu' : 'Chưa xác định') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-line"><strong>Ghế:</strong> <span
                                            id="selected-seats">{{ $selectedSeats ?? 'Chưa chọn ghế' }}</span></div>
                                    <div class="info-line"><strong>Tiền vé:</strong> <span
                                            id="ticket-price">{{ number_format($totalPrice ?? 0) }} ₫</span></div>
                                    <hr style="border: none; height: 2px; margin: 8px 0;">
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
                                    <div class="info-line mt-1"><strong>Tổng tiền đồ ăn:</strong> <span id="snack-total">0
                                            ₫</span></div>
                                    <hr style="border: none; height: 2px; background-color: #e5006e; margin: 8px 0;">
                                    <div class="info-line"><strong>Tổng cộng:</strong> <span id="summary-total">0 ₫</span>
                                    </div>
                                    <div class="timer-section mt-4" id="timer-section">
                                        <h2 style="color: #ffffff; font-size: 1.2em; margin-bottom: 10px;"><strong>Time
                                                Remaining</strong></h2>
                                        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;"
                                            id="timer-display">
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
                                    class="previous-step custom-btn bg-gray-600 hover:bg-gray-700" value="Back" />
                                <input type="button" name="next-step" id="proceed-snack-btn"
                                    class="next-step custom-btn bg-[#e5006e] hover:bg-[#c4005c]"
                                    value="Proceed to Payment" />
                            </div>
                        </fieldset>
                        <!-- Bước 4: Payment -->
                        <fieldset>
                            <div class="payment-container step-content" style="display: flex; gap: 24px;">
                                <!-- Left Section: Payment Details -->
                                <div class="summary-section"
                                    style="flex: 2; background: #1c1c1c; border-radius: 10px; padding: 24px; border: 2px solid #333;">
                                    <h3 class="summary-title" style="color: #fff;">Thanh toán</h3>
                                    <div style="margin-bottom: 20px;">
                                        <h4 style="color: #e5006e; font-size: 1.1em; margin-bottom: 8px;">
                                            Mã giảm giá
                                            @if(isset($userRank))
                                                <span class="user-rank-display user-rank-{{ strtolower(str_replace(' ', '-', $userRank->name)) }}">
                                                    {{ $userRank->name }}
                                                </span>
                                            @endif
                                        </h4>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="text" id="promotion-code-input"
                                                placeholder="Nhập mã giảm giá"
                                                style="flex: 1; padding: 12px; border: 2px solid #444; border-radius: 8px; background: #1a1a1a; color: #fff; transition: all 0.3s ease; font-size: 14px; font-weight: 500;"
                                                onfocus="this.style.borderColor='#e5006e'; this.style.boxShadow='0 0 8px rgba(229, 0, 110, 0.3)'; this.style.background='#222';"
                                                onblur="this.style.borderColor='#444'; this.style.boxShadow='none'; this.style.background='#1a1a1a';" />
                                            <button id="apply-promotion-btn"
                                                style="padding: 12px 24px; background: linear-gradient(135deg, #e5006e, #c4005c); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; font-size: 14px; box-shadow: 0 4px 8px rgba(229, 0, 110, 0.3);"
                                                onmouseover="this.style.background='linear-gradient(135deg, #c4005c, #a3004a)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(229, 0, 110, 0.4)';"
                                                onmouseout="this.style.background='linear-gradient(135deg, #e5006e, #c4005c)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(229, 0, 110, 0.3)';">
                                                Áp dụng
                                            </button>
                                            <button id="reset-promotion-btn" 
                                                style="display: none; padding: 12px 20px; background: linear-gradient(135deg, #6b7280, #4b5563); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; font-size: 14px; box-shadow: 0 4px 8px rgba(107, 114, 128, 0.3);"
                                                onmouseover="this.style.background='linear-gradient(135deg, #4b5563, #374151)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(107, 114, 128, 0.4)';"
                                                onmouseout="this.style.background='linear-gradient(135deg, #6b7280, #4b5563)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(107, 114, 128, 0.3)';">
                                                Đổi mã
                                            </button>
                                        </div>
                                        
                                        <!-- Available Promotions Section -->
                                        <div id="available-promotions-section" style="margin-top: 15px; display: none;">
                                            <!-- Enhanced Global search for all promotions -->
                                            <div class="promotion-search-box">
                                                <input type="text" id="global-promotion-search" class="promotion-search-input" 
                                                       placeholder="🔍 Tìm kiếm theo tên mã, loại hạng, hoặc mô tả..." 
                                                       onkeyup="searchPromotions(this.value)"
                                                       oninput="updateSearchResults(this.value)">
                                                <span class="promotion-search-icon">🔍</span>
                                                <div id="search-results-indicator" class="promotion-search-results"></div>
                                            </div>
                                            
                                            <!-- Refresh promotions button -->
                                            <div style="margin: 10px 0; text-align: right;">
                                                <button id="refresh-promotions-btn" onclick="refreshPromotionData()" 
                                                        class="refresh-promotions-btn"
                                                        title="Cập nhật danh sách mã giảm giá mới nhất từ hệ thống. Bạn cũng có thể nhấn F5 khi đang focus vào phần này."
                                                        style="background: #f3f4f6; border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; color: #6b7280; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;"
                                                        onmouseover="this.style.background='#e5e7eb'; this.style.borderColor='#9ca3af';"
                                                        onmouseout="this.style.background='#f3f4f6'; this.style.borderColor='#d1d5db';">
                                                    <i class="fas fa-sync-alt"></i> Làm mới
                                                </button>
                                                <small style="display: block; color: #9ca3af; font-size: 11px; margin-top: 4px;">
                                                    💡 Mẹo: Nhấn F5 để cập nhật nhanh
                                                </small>
                                            </div>
                                            
                                            <!-- Promotion statistics -->
                                            <div id="promotion-stats" class="promotion-stats"></div>
                                            
                                            <!-- Rank-specific promotions -->
                                            <div id="rank-promotions-section" class="promotion-section rank-section" style="display: none;">
                                                <div class="promotion-section-title" onclick="togglePromotionSection('rank')">
                                                    <span>
                                                        🏆 Mã giảm giá dành cho hạng của bạn
                                                        <span id="rank-promotions-count" class="promotion-count-badge">0</span>
                                                    </span>
                                                    <span class="promotion-toggle-icon">▼</span>
                                                </div>
                                                <div id="rank-promotions-content" class="promotion-content">
                                                    <div id="rank-promotions-list" class="promotion-grid"></div>
                                                    <div id="rank-show-more" class="promotion-show-more" style="display: none;" onclick="showMorePromotions('rank')">
                                                        <span id="rank-load-text">Xem thêm mã giảm giá...</span>
                                                        <div id="rank-loading" class="promotion-loading" style="display: none;">⏳ Đang tải...</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- General promotions -->
                                            <div id="general-promotions-section" class="promotion-section general-section" style="display: none;">
                                                <div class="promotion-section-title" onclick="togglePromotionSection('general')">
                                                    <span>
                                                        🎁 Mã giảm giá chung
                                                        <span id="general-promotions-count" class="promotion-count-badge">0</span>
                                                    </span>
                                                    <span class="promotion-toggle-icon">▼</span>
                                                </div>
                                                <div id="general-promotions-content" class="promotion-content">
                                                    <div id="general-promotions-list" class="promotion-grid"></div>
                                                    <div id="general-show-more" class="promotion-show-more" style="display: none;" onclick="showMorePromotions('general')">
                                                        <span id="general-load-text">Xem thêm mã giảm giá...</span>
                                                        <div id="general-loading" class="promotion-loading" style="display: none;">⏳ Đang tải...</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Higher rank promotions (if user can see them) -->
                                            <div id="higher-rank-promotions-section" class="promotion-section" style="display: none; border-left-color: #e11d48;">
                                                <div class="promotion-section-title" onclick="togglePromotionSection('higher')">
                                                    <span>
                                                        ✨ Mã giảm giá hạng cao hơn
                                                        <span id="higher-rank-promotions-count" class="promotion-count-badge">0</span>
                                                    </span>
                                                    <span class="promotion-toggle-icon collapsed">▼</span>
                                                </div>
                                                <div id="higher-rank-promotions-content" class="promotion-content collapsed">
                                                    <div id="higher-rank-promotions-list" class="promotion-grid"></div>
                                                    <div id="higher-rank-show-more" class="promotion-show-more" style="display: none;" onclick="showMorePromotions('higher')">
                                                        <span id="higher-rank-load-text">Xem thêm mã hạng cao...</span>
                                                        <div id="higher-rank-loading" class="promotion-loading" style="display: none;">⏳ Đang tải...</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- No results found -->
                                            <div id="promotion-no-results" class="promotion-empty-state" style="display: none;">
                                                <div class="promotion-empty-state-icon">🔍</div>
                                                <p>Không tìm thấy mã giảm giá phù hợp</p>
                                                <small>Hãy thử từ khóa khác hoặc xóa bộ lọc</small>
                                            </div>
                                        </div>
                                        
                                        <div id="promotion-feedback"
                                            style="color: #aaa; font-size: 0.9em; margin-top: 5px;"></div>
                                    </div>
                                    <div style="margin-bottom: 20px;">
                                        <h4 style="color: #e5006e; font-size: 1.1em; margin-bottom: 8px;">Điểm thưởng</h4>
                                        
                                        <div>
                                            <strong>Điểm khả dụng:</strong> <span id="available-points"
                                                style="color:#e5006e">{{ $userPoints }}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="number" id="points-input" placeholder="Nhập số điểm sử dụng"
                                                min="0" max="{{ $userPoints }}" value="0"
                                                style="flex: 1; padding: 10px; border: 1px solid #444; border-radius: 6px; background: #222; color: #fff;" />
                                            <button id="apply-points-btn"
                                                style="padding: 10px 20px; background: #e5006e; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.3s;">Đổi
                                                điểm</button>
                                        </div>
                                        <div style="color: #aaa; font-size: 0.95em; margin-top: 4px;">
                                            1 điểm = 1,000₫ • Tổng giảm giá tối đa 30%
                                        </div>
                                    </div>
                                    <div>
                                        <h4 style="color: #e5006e; font-size: 1.1em; margin-bottom: 10px;">Phương thức
                                            thanh toán</h4>
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <label style="display: flex; align-items: center; gap: 10px;">
                                                <input type="radio" name="payment-method" checked
                                                    style="accent-color: #e5006e;" />
                                                <span style="color: #fff;">Thẻ ngân hàng</span>
                                            </label>
                                            <label style="display: flex; align-items: center; gap: 10px;">
                                                <input type="radio" name="payment-method"
                                                    style="accent-color: #e5006e;" />
                                                <span style="color: #fff;">Ví điện tử</span>
                                            </label>
                                            <label style="display: flex; align-items: center; gap: 10px;">
                                                <input type="radio" name="payment-method"
                                                    style="accent-color: #e5006e;" />
                                                <span style="color: #fff;">Tiền mặt tại quầy</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Right Section: Thông tin đặt vé -->
                                <div class="summary-section" id="payment-summary-section" style="flex: 1;">
                                    <h3 class="summary-title">Thông tin đặt vé</h3>
                                    <div class="ticket-info">
                                        <div class="movie-poster">
                                            <img id="payment-movie-poster"
                                                src="{{ $movie->image_path ? asset('storage/' . $movie->image_path) : asset('images/default-poster.jpg') }}"
                                                alt="{{ $movie->title ?? 'Chưa xác định' }}" class="poster-image" />
                                        </div>
                                        <div class="info-details">
                                            <div class="info-line"><strong>Phim:</strong> <span
                                                    id="payment-movie-title">{{ $movie->title ?? 'Chưa xác định' }}</span>
                                            </div>
                                            <div class="info-line"><strong>Rạp:</strong> <span
                                                    id="payment-cinema-name">{{ $cinema->name ?? ($showtimeId ? 'Chưa chọn suất chiếu' : 'Chưa xác định') }}</span>
                                            </div>
                                            <div class="info-line"><strong>Suất:</strong> <span
                                                    id="payment-showtime">{{ $showtime?->start_time?->format('H:i d/m/Y') ?? ($showtimeId ? 'Chưa chọn suất chiếu' : 'Chưa xác định') }}</span>
                                            </div>
                                            <div class="info-line"><strong>Phòng:</strong> <span
                                                    id="payment-room-name">{{ $room->name ?? ($showtimeId ? 'Chưa chọn suất chiếu' : 'Chưa xác định') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-line"><strong>Ghế:</strong> <span
                                            id="payment-selected-seats">{{ $selectedSeats ?? 'Chưa chọn ghế' }}</span>
                                    </div>
                                    <div class="info-line"><strong>Tiền vé:</strong> <span
                                            id="payment-ticket-price">{{ number_format($totalPrice ?? 0) }} ₫</span></div>
                                    <div class="info-line"><strong>Giảm giá mã:</strong> <span
                                            id="voucher-discount-line">0 ₫</span></div>
                                    <div class="info-line"><strong>Giảm giá:</strong> <span
                                            id="discountDisplay">0 ₫</span></div>
                                    <div id="auto-promotion-info" class="auto-promotion-info"></div>
                                    <div class="info-line"><strong>Điểm đã dùng:</strong> <span
                                            id="points-used-line">0</span></div>
                                    <hr style="border: none; height: 2px; margin: 8px 0;">
                                    <table id="payment-snack-table" class="snack-table text-sm mt-2">
                                        <thead>
                                            <tr>
                                                <th>Tên món</th>
                                                <th>Số lượng</th>
                                                <th>Giá</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div class="info-line"><strong>Tổng tiền đồ ăn:</strong> <span
                                            id="payment-snack-total">0 ₫</span></div>
                                    <div class="info-line"><strong>Tổng phụ:</strong> <span id="subtotalDisplay">0
                                            ₫</span></div>
                                    <hr style="border: none; height: 2px; background-color: #e5006e; margin: 8px 0;">
                                    <div class="info-line"><strong>Tổng cộng:</strong> <span id="totalDisplay">0 ₫</span>
                                    </div>
                                    <div class="info-line"><strong>Tổng thanh toán:</strong> <span
                                            id="paymentSummaryTotal">0 ₫</span></div> <!-- Thêm phần tử này -->
                                    <div class="info-line"><strong>Danh sách đồ ăn:</strong> <span id="snackItems">Chưa
                                            chọn đồ ăn</span></div>
                                    <div class="timer-section mt-4" id="timer-section-payment">
                                        <h2 style="color: #ffffff; font-size: 1.2em; margin-bottom: 10px;"><strong>Time
                                                Remaining</strong></h2>
                                        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;"
                                            id="timer-display-payment">
                                            <div style="text-align: center;">
                                                <span id="hours-payment" class="timer-box">00</span>
                                                <div class="timer-label">Giờ</div>
                                            </div>
                                            <div style="text-align: center;">
                                                <span id="minutes-payment" class="timer-box">00</span>
                                                <div class="timer-label">Phút</div>
                                            </div>
                                            <div style="text-align: center;">
                                                <span id="seconds-payment" class="timer-box">00</span>
                                                <div class="timer-label">Giây</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between mt-4 px-4">
                                <input type="button" name="previous-step" id="back-btn"
                                    class="previous-step custom-btn bg-gray-600 hover:bg-gray-700" value="Back" />
                                <input type="button" name="next-step" id="proceed-payment-btn"
                                    class="next-step custom-btn bg-[#e5006e] hover:bg-[#c4005c]"
                                    value="Proceed to Payment" />
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
                                    onclick="location.href='{{ route('client.home') }}';" />
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
    console.log('Script started loading...');
    
    let currentStep = 1;
    let selectedTime = null;
    let selectedShowtimeId = null;
    let selectedDate = @json($dates[0]['full_date'] ?? '');
    let selectedSeats = [];
    let ticketPrice = 0;
    let snackTotal = 0;
    let discount = 0; // Tổng giảm giá (mã + điểm)
    let promotionDiscount = 0; // Giảm giá từ mã khuyến mãi
    let pointsDiscount = 0; // Giảm giá từ điểm
    let cinemaName = 'N/A';
    const showtimesData = @json($showtimesData);
    const roomsData = @json($roomsData);
    const movieTitle = @json($movie->name ?? 'N/A');
    let variantData = {};
    let countdownInterval = null;
    let countdownEndTime = null;
    let prevId = "1"; // Biến toàn cục cho myFunction
    let autoRefreshInterval = null; // Auto refresh interval cho promotion data

    console.log('Variables initialized:', {
        currentStep,
        selectedDate,
        movieTitle,
        showtimesDataLength: Object.keys(showtimesData).length
    });

    // Hàm định dạng số
    function numberFormat(number) {
        if (number === null || number === undefined || isNaN(number)) {
            return "0";
        }
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Hàm định dạng ngày
    function formatDateDisplay(dateStr) {
        const dateObj = new Date(dateStr);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Hàm bắt đầu đếm ngược
    function startCountdown(durationInSeconds) {
        const endTime = new Date().getTime() + durationInSeconds * 1000;
        countdownEndTime = endTime;
        updateTimerDisplay();
        if (countdownInterval) clearInterval(countdownInterval);
        countdownInterval = setInterval(updateTimerDisplay, 1000);
    }

    // Hàm cập nhật hiển thị đếm ngược
    function updateTimerDisplay() {
        if (!countdownEndTime) return;
        const now = new Date().getTime();
        let distance = countdownEndTime - now;

        if (distance <= 0) {
            clearInterval(countdownInterval);
            countdownInterval = null;
            ['hours-snack', 'minutes-snack', 'seconds-snack', 'hours-payment', 'minutes-payment', 'seconds-payment']
            .forEach(id => {
                const element = document.getElementById(id);
                if (element) element.textContent = "00";
            });
            countdownEndTime = null;
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

    // Hàm xử lý khi iframe tải thành công
    function onIframeLoad() {
        console.log('Iframe loaded');
        const iframe = document.getElementById('seat-map-iframe');
        if (iframe && iframe.contentWindow && selectedShowtimeId) {
            iframe.contentWindow.postMessage({
                showtimeId: selectedShowtimeId
            }, '*');
        }
    }

    // Hàm xử lý khi iframe lỗi
    function onIframeError() {
        console.error('Iframe failed to load');
        const iframe = document.getElementById('seat-map-iframe');
        const placeholder = document.getElementById('seat-map-placeholder');
        if (iframe && placeholder) {
            placeholder.innerHTML = '<p>Không thể tải bản đồ ghế. Vui lòng chọn suất chiếu khác.</p>';
            placeholder.style.display = 'block';
            iframe.style.display = 'none';
        }
    }

    // Nhận dữ liệu ghế từ iframe
    window.receiveSeats = function(data) {
        console.log('Data received in parent window:', data);
        selectedSeats = Array.isArray(data.seats) ? data.seats : [];
        cinemaName = data.cinemaName || 'N/A';
        sessionStorage.setItem('cinemaName', cinemaName);
        console.log('Updated selectedSeats and cinemaName in parent:', {
            selectedSeats,
            cinemaName
        });
        updateOrderSummary();
    };

    // Hàm chọn ngày
    function myFunction(id, date) {
        document.getElementById(prevId).style.background = "rgb(243, 235, 235)";
        document.getElementById(id).style.background = "#df0e62";
        prevId = id;
        selectedDate = date;
        selectedTime = null;
        selectedShowtimeId = null;
        selectedSeats = [];
        document.getElementById("screen-next-btn").disabled = true;
        const iframe = document.getElementById('seat-map-iframe');
        const placeholder = document.getElementById('seat-map-placeholder');
        if (iframe) iframe.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
        updateShowtimes(date);
        updateOrderSummary();
    }

    // Hàm cập nhật suất chiếu
    function updateShowtimes(date) {
        console.log('Date:', date);
        console.log('Showtimes Data:', showtimesData);
        const timeUl = document.getElementById('time-ul');
        timeUl.innerHTML = '';
        const rooms = showtimesData[date] || [];
        if (rooms.length === 0) {
            timeUl.innerHTML =
                '<li class="time-li">Không có suất chiếu nào cho ngày này. Vui lòng kiểm tra lại dữ liệu hoặc chọn ngày khác.</li>';
            console.warn('No showtimes available for date:', date);
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

    // Hàm chọn thời gian
    function timeFunction(showtimeId, time, basePrice) {
        console.log('Executing timeFunction with showtimeId:', showtimeId);
        selectedTime = time;
        selectedShowtimeId = showtimeId;
        ticketPrice = basePrice;
        selectedSeats = [];
        document.getElementById("screen-next-btn").disabled = false;
        updateOrderSummary();

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
                iframe.onload = () => console.log('Iframe loaded successfully');
                iframe.onerror = () => {
                    console.error('Iframe failed to load');
                    placeholder.innerHTML = '<p>Không thể tải bản đồ ghế. Vui lòng chọn suất chiếu khác.</p>';
                    placeholder.style.display = 'block';
                    iframe.style.display = 'none';
                };
            } catch (error) {
                console.error('Error updating iframe:', error);
                placeholder.innerHTML = '<p>Đã xảy ra lỗi khi tải bản đồ ghế.</p>';
                placeholder.style.display = 'block';
                iframe.style.display = 'none';
            }
        } else {
            console.error('Iframe or placeholder not found in timeFunction');
        }
    }

    // Hàm cập nhật biến thể sản phẩm
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
            updateSummaryTable(variantKey, productName, variantData[productId].variantName, quantity, variantData[
                productId].price);
            snackTotal = calculateSnackTotal();
            updateOrderSummary();
        }
    }

    // Hàm cập nhật số lượng
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

    // Hàm cập nhật bảng tóm tắt
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

    // Hàm tính tổng tiền đồ ăn
    function calculateSnackTotal() {
        let snackSubtotal = 0;
        document.querySelectorAll('#summary-table-body tr[data-variant-key]').forEach(row => {
            const priceText = row.children[2].textContent.replace(' ₫', '').replace(/,/g, '');
            snackSubtotal += parseFloat(priceText) || 0;
        });
        return snackSubtotal;
    }


    // Hàm áp dụng mã giảm giá tự động
    function applyPromotionAutomatically() {        console.log('Attempting to apply promotion automatically...');
        const subtotalElement = document.getElementById('subtotalDisplay');
        const subtotalText = subtotalElement ? subtotalElement.textContent : '0 ₫';
        const subtotal = parseInt(subtotalText.replace(/[^\d]/g, '')) || 0;
        
        if (subtotal <= 0) {
            console.log('Subtotal is 0 or negative, skipping auto promotion');
            return;
        }
        
        // Kiểm tra đơn hàng tối thiểu cho auto promotion
        const MINIMUM_ORDER_AMOUNT = 200000; // 200,000 VND - Thống nhất với manual promotion
        if (subtotal < MINIMUM_ORDER_AMOUNT) {
            console.log(`Order subtotal ${subtotal} is below minimum ${MINIMUM_ORDER_AMOUNT}, skipping auto promotion`);
            const promotionFeedback = document.getElementById('promotion-feedback');
            if (promotionFeedback) {
                const remaining = MINIMUM_ORDER_AMOUNT - subtotal;
                const percentage = (subtotal / MINIMUM_ORDER_AMOUNT * 100).toFixed(1);
                
                promotionFeedback.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span>� Tiến độ đơn hàng:</span>
                        <div style="flex: 1; background: rgba(255,255,255,0.1); border-radius: 10px; height: 6px; overflow: hidden;">
                            <div style="width: ${percentage}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #1d4ed8); transition: width 0.3s ease;"></div>
                        </div>
                        <span style="font-size: 0.8em;">${percentage}%</span>
                    </div>
                    <small>💡 Còn ${numberFormat(remaining)} ₫ nữa để sử dụng mã giảm giá</small>
                `;
                promotionFeedback.className = 'promotion-feedback info';
            }
            return;
        }

        console.log('Order subtotal for auto promotion:', subtotal);

        fetch('{{ route('client.applyPromotionAuto') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_amount: subtotal  // Sử dụng subtotal thay vì total
                })
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        // Nếu response không ok nhưng có data, ném lỗi với data
                        throw { status: response.status, data: data };
                    }
                    return data;
                });
            })
            .then(data => {
                console.log('Auto promotion response:', data);
                
                // Double check điều kiện đơn hàng tối thiểu
                const currentSubtotalElement = document.getElementById('subtotalDisplay');
                const currentSubtotalText = currentSubtotalElement ? currentSubtotalElement.textContent : '0 ₫';
                const currentSubtotal = parseInt(currentSubtotalText.replace(/[^\d]/g, '')) || 0;
                const MINIMUM_ORDER_AMOUNT = 200000;
                
                if (currentSubtotal < MINIMUM_ORDER_AMOUNT) {
                    console.warn('Order subtotal changed during auto promotion request, current subtotal:', currentSubtotal);
                    return;
                }
                
                if (data.success) {
                    // Kiểm tra mã giảm giá có phù hợp với giá trị đơn hàng không
                    const promotionValue = data.discount || 0;
                    if (promotionValue > currentSubtotal) {
                        console.log(`Auto promotion value ${promotionValue} exceeds order subtotal ${currentSubtotal}, cannot apply`);
                        
                        // Reset discount và không áp dụng mã
                        promotionDiscount = 0;
                        discount = pointsDiscount; // Chỉ giữ lại discount từ điểm
                        document.getElementById('voucher-discount-line').textContent = '0 ₫';
                        document.getElementById('discountDisplay').textContent = numberFormat(discount) + ' ₫';
                        
                        const promotionFeedback = document.getElementById('promotion-feedback');
                        if (promotionFeedback) {
                            promotionFeedback.innerHTML = `💡 Đơn hàng của bạn chưa đủ điều kiện cho các mã giảm giá hiện có`;
                            promotionFeedback.className = 'promotion-feedback info';
                        }
                        
                        updateOrderSummary();
                        return;
                    }
                    
                    // Kiểm tra giới hạn 30% tổng đơn hàng
                    const currentPointsDiscount = parseInt(document.getElementById('points-used-line').textContent || '0') * 1000;
                    const maxTotalDiscount = currentSubtotal * 0.3;
                    const potentialTotalDiscount = promotionValue + currentPointsDiscount;
                    
                    if (potentialTotalDiscount > maxTotalDiscount) {
                        console.log(`Total discount would exceed 30% limit: ${potentialTotalDiscount} > ${maxTotalDiscount}`);
                        
                        // Reset discount và không áp dụng mã
                        promotionDiscount = 0;
                        discount = pointsDiscount; // Chỉ giữ lại discount từ điểm
                        document.getElementById('voucher-discount-line').textContent = '0 ₫';
                        document.getElementById('discountDisplay').textContent = numberFormat(discount) + ' ₫';
                        
                        const promotionFeedback = document.getElementById('promotion-feedback');
                        if (promotionFeedback) {
                            promotionFeedback.innerHTML = `⚠️ Tổng giảm giá không được vượt quá 30% đơn hàng (hiện tại đã dùng ${numberFormat(currentPointsDiscount)}₫ từ điểm)`;
                            promotionFeedback.className = 'promotion-feedback warning';
                        }
                        
                        updateOrderSummary();
                        return;
                    }
                    
                    // Validate discount amount không được lớn hơn order subtotal
                    const maxDiscount = Math.min(promotionValue, currentSubtotal);
                    promotionDiscount = maxDiscount;
                    discount = promotionDiscount + pointsDiscount; // Tổng discount
                    promotionId = data.promotion_id;

                    // Cập nhật display của discount
                    document.getElementById('discountDisplay').textContent = numberFormat(discount) + ' ₫';

                    // Hiển thị thông tin mã giảm giá được áp dụng
                    const promotionFeedback = document.getElementById('promotion-feedback');
                    if (promotionFeedback) {
                        let feedbackText = `${data.promotion_type || 'Mã giảm giá tự động'}: <strong>${data.promotion_code}</strong> - ${data.promotion_name}`;
                        
                        // Hiển thị thông tin về rank của mã giảm giá
                        if (data.promotion_rank && data.promotion_rank !== 'Chung') {
                            feedbackText += ` (Dành cho hạng: ${data.promotion_rank})`;
                        }
                        
                        if (data.user_rank && data.user_rank !== 'Khách thường') {
                            feedbackText += ` - Hạng của bạn: ${data.user_rank}`;
                        }
                        
                        // Hiển thị giảm giá theo đúng loại
                        if (data.discount_type === 'percentage') {
                            feedbackText += ` (Giảm: ${data.discount_value}%`;
                            if (data.max_discount) {
                                feedbackText += `, tối đa ${numberFormat(data.max_discount)} ₫`;
                            }
                            feedbackText += `)`;
                        } else {
                            feedbackText += ` (Giảm: ${numberFormat(data.discount_value)} ₫)`;
                        }
                        
                        promotionFeedback.innerHTML = feedbackText;
                        promotionFeedback.style.color = '#22c55e';
                    }

                    // Hiển thị thông tin trong payment step
                    const autoPromotionInfo = document.getElementById('auto-promotion-info');
                    if (autoPromotionInfo) {
                        let infoHtml = `<strong>🎉 ${data.promotion_type || 'Mã giảm giá tự động'}:</strong><br>`;
                        infoHtml += `<span style="font-weight: 600;">${data.promotion_code}</span> - ${data.promotion_name}`;
                        
                        // Thêm badge cho loại mã giảm giá
                        if (data.promotion_type && data.promotion_type.includes('hạng')) {
                            infoHtml += `<span class="promotion-type-badge rank-badge">${data.promotion_rank || 'VIP'}</span><br>`;
                            autoPromotionInfo.className = 'auto-promotion-info rank-promotion show';
                            console.log(`Applied ${data.promotion_rank || 'VIP'} rank promotion:`, data.promotion_code);
                        } else {
                            infoHtml += `<span class="promotion-type-badge general-badge">CHUNG</span><br>`;
                            autoPromotionInfo.className = 'auto-promotion-info general-promotion show';
                            console.log('Applied general promotion:', data.promotion_code);
                        }
                        
                        // Hiển thị thông tin rank nếu có
                        if (data.promotion_rank && data.promotion_rank !== 'Chung') {
                            infoHtml += `<small>Dành cho hạng: <strong>${data.promotion_rank}</strong></small><br>`;
                        }
                        
                        if (data.user_rank && data.user_rank !== 'Khách thường') {
                            infoHtml += `<small>Hạng của bạn: <strong>${data.user_rank}</strong></small><br>`;
                        }
                        
                        // Hiển thị giảm giá theo đúng loại
                        if (data.discount_type === 'percentage') {
                            infoHtml += `<small>Giảm: <strong>${data.discount_value}%`;
                            if (data.max_discount) {
                                infoHtml += `, tối đa ${numberFormat(data.max_discount)} ₫`;
                            }
                            infoHtml += `</strong></small>`;
                        } else {
                            infoHtml += `<small>Giảm: <strong>${numberFormat(data.discount_value)} ₫</strong></small>`;
                        }
                        
                        autoPromotionInfo.innerHTML = infoHtml;
                    }

                    // Cập nhật dòng "Giảm giá mã:" và "Giảm giá:" theo loại mã
                    const voucherDiscountElement = document.getElementById('voucher-discount-line');
                    const discountDisplayElement = document.getElementById('discountDisplay');
                    
                    if (voucherDiscountElement) {
                        // Hiển thị theo loại mã: percentage hiển thị %, fixed hiển thị số tiền
                        voucherDiscountElement.textContent = data.display_text || (data.discount_type === 'percentage' ? `${data.discount_value}%` : `${numberFormat(data.discount_value)} ₫`);
                    }
                    
                    // "Giảm giá:" luôn hiển thị số tiền thực tế được giảm
                    if (discountDisplayElement) {
                        discountDisplayElement.textContent = `${numberFormat(maxDiscount)} ₫`;
                    }

                    // Cập nhật input mã giảm giá nếu có
                    const promotionCodeInput = document.getElementById('promotion-code-input');
                    if (promotionCodeInput) {
                        promotionCodeInput.value = data.promotion_code;
                        promotionCodeInput.readOnly = true;
                        promotionCodeInput.style.backgroundColor = '#f3f4f6';
                    }

                    console.log('Auto promotion applied successfully:', {
                        'promotion_code': data.promotion_code,
                        'promotion_name': data.promotion_name,
                        'promotion_type': data.promotion_type,
                        'promotion_rank': data.promotion_rank,
                        'user_rank': data.user_rank,
                        'discount': data.discount
                    });
                    updateOrderSummary();
                } else {
                    console.log('No suitable promotion found or error:', data.message);
                    // Reset discount nếu không có mã giảm giá phù hợp
                    discount = 0;
                    document.getElementById('voucher-discount-line').textContent = '0 ₫';
                    document.getElementById('discountDisplay').textContent = '0 ₫';

                    // Ẩn thông tin mã giảm giá tự động
                    const autoPromotionInfo = document.getElementById('auto-promotion-info');
                    if (autoPromotionInfo) {
                        autoPromotionInfo.classList.remove('show');
                        autoPromotionInfo.innerHTML = '';
                    }

                    // Hiển thị thông báo nếu chưa đăng nhập
                    if (data.message && data.message.includes('đăng nhập')) {
                        const promotionFeedback = document.getElementById('promotion-feedback');
                        if (promotionFeedback) {
                            promotionFeedback.textContent = data.message;
                            promotionFeedback.style.color = '#f59e0b';
                        }
                    }

                    updateOrderSummary();
                }
            })
            .catch(error => {
                console.error('Error in auto promotion:', error);
                
                // Kiểm tra nếu là lỗi từ server với data
                if (error.status && error.data) {
                    console.log('Auto promotion server error:', error.data);
                    const promotionFeedback = document.getElementById('promotion-feedback');
                    if (promotionFeedback && error.data.message) {
                        promotionFeedback.innerHTML = error.data.message;
                        promotionFeedback.className = 'promotion-feedback info';
                    }
                }
                
                // Reset discount khi có lỗi
                discount = 0;
                document.getElementById('voucher-discount-line').textContent = '0 ₫';
                document.getElementById('discountDisplay').textContent = '0 ₫';

                // Ẩn thông tin mã giảm giá tự động
                const autoPromotionInfo = document.getElementById('auto-promotion-info');
                if (autoPromotionInfo) {
                    autoPromotionInfo.classList.remove('show');
                    autoPromotionInfo.innerHTML = '';
                }

                updateOrderSummary();
            });
    }

    // Hàm áp dụng mã giảm giá chung (để dùng chung cho cả manual và select)
    function applyPromotion() {
        const applyPromotionBtn = document.getElementById('apply-promotion-btn');
        const promotionFeedback = document.getElementById('promotion-feedback');
        
        if (applyPromotionBtn) applyPromotionBtn.disabled = true;

        // Ẩn thông tin mã giảm giá tự động khi áp dụng thủ công
        const autoPromotionInfo = document.getElementById('auto-promotion-info');
        if (autoPromotionInfo) {
            autoPromotionInfo.classList.remove('show');
            autoPromotionInfo.innerHTML = '';
        }

        const code = document.getElementById('promotion-code-input').value.trim();
        const subtotalElement = document.getElementById('subtotalDisplay');
        const subtotalText = subtotalElement ? subtotalElement.textContent : '0 ₫';
        const subtotal = parseInt(subtotalText.replace(/[^\d]/g, '')) || 0;
        console.log('Applying promotion with code:', code, 'and subtotal:', subtotal);
        console.log('Request payload:', {
            code: code,
            order_amount: subtotal
        });
        
        // Kiểm tra đơn hàng tối thiểu
        const MINIMUM_ORDER_AMOUNT = 200000; // 200,000 VND
        if (subtotal < MINIMUM_ORDER_AMOUNT) {
            if (promotionFeedback) {
                const remaining = MINIMUM_ORDER_AMOUNT - subtotal;
                const percentage = (subtotal / MINIMUM_ORDER_AMOUNT * 100).toFixed(1);
                
                promotionFeedback.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span>📊 Tiến độ đơn hàng:</span>
                        <div style="flex: 1; background: rgba(255,255,255,0.1); border-radius: 10px; height: 6px; overflow: hidden;">
                            <div style="width: ${percentage}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #1d4ed8); transition: width 0.3s ease;"></div>
                        </div>
                        <span style="font-size: 0.8em;">${percentage}%</span>
                    </div>
                    <small>⚠️ Đơn hàng tối thiểu ${numberFormat(MINIMUM_ORDER_AMOUNT)} ₫ để sử dụng mã giảm giá. Còn ${numberFormat(remaining)} ₫ nữa!</small>
                `;
                promotionFeedback.className = 'promotion-feedback warning';
            }
            if (applyPromotionBtn) applyPromotionBtn.disabled = false;
            return;
        }
        
        if (!code) {
            if (promotionFeedback) {
                promotionFeedback.textContent = 'Vui lòng nhập mã giảm giá.';
                promotionFeedback.className = 'promotion-feedback warning';
            }
            if (applyPromotionBtn) applyPromotionBtn.disabled = false;
            return;
        }
        
        fetch('{{ route('client.applyPromotion') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    order_amount: subtotal  // Sử dụng subtotal thay vì total
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', [...response.headers.entries()]);
                
                // Kiểm tra Content-Type trước khi parse JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => {
                        console.log('Response data:', data);
                        if (!response.ok) {
                            // Nếu response không ok nhưng có data, ném lỗi với data
                            throw { status: response.status, data: data };
                        }
                        return data;
                    });
                } else {
                    // Nếu không phải JSON, đọc như text
                    return response.text().then(text => {
                        console.log('Response text:', text);
                        throw { 
                            status: response.status, 
                            data: { 
                                error: response.ok ? 'Phản hồi không hợp lệ từ server.' : `Lỗi ${response.status}: ${response.statusText}`,
                                raw_response: text
                            } 
                        };
                    });
                }
            })
            .then(data => {
                console.log('Manual promotion response:', data);
                
                // Double check điều kiện đơn hàng tối thiểu trước khi áp dụng
                const currentSubtotalElement = document.getElementById('subtotalDisplay');
                const currentSubtotalText = currentSubtotalElement ? currentSubtotalElement.textContent : '0 ₫';
                const currentSubtotal = parseInt(currentSubtotalText.replace(/[^\d]/g, '')) || 0;
                const MINIMUM_ORDER_AMOUNT = 200000;
                
                if (currentSubtotal < MINIMUM_ORDER_AMOUNT) {
                    console.warn('Order subtotal changed during request, current subtotal:', currentSubtotal);
                    if (promotionFeedback) {
                        promotionFeedback.innerHTML = `⚠️ Đơn hàng không đủ điều kiện tối thiểu ${numberFormat(MINIMUM_ORDER_AMOUNT)} ₫`;
                        promotionFeedback.className = 'promotion-feedback error';
                    }
                    if (applyPromotionBtn) applyPromotionBtn.disabled = false;
                    return;
                }
                
                if (data.success) {
                    // Kiểm tra giới hạn 30% tổng đơn hàng
                    const currentPointsDiscount = parseInt(document.getElementById('points-used-line').textContent || '0') * 1000;
                    let promotionDiscountValue = data.discount || 0;
                    const maxTotalDiscount = currentSubtotal * 0.3;
                    const potentialTotalDiscount = promotionDiscountValue + currentPointsDiscount;
                    
                    if (potentialTotalDiscount > maxTotalDiscount) {
                        if (promotionFeedback) {
                            promotionFeedback.innerHTML = `⚠️ Tổng giảm giá không được vượt quá 30% đơn hàng (hiện tại đã dùng ${numberFormat(currentPointsDiscount)}₫ từ điểm)`;
                            promotionFeedback.className = 'promotion-feedback warning';
                        }
                        if (applyPromotionBtn) applyPromotionBtn.disabled = false;
                        return;
                    }
                    
                    // Validate discount amount không được lớn hơn order subtotal
                    const maxDiscount = Math.min(promotionDiscountValue, currentSubtotal);
                    promotionDiscount = maxDiscount;
                    discount = promotionDiscount + pointsDiscount; // Tổng discount
                    
                    // Cập nhật hiển thị theo loại mã giảm giá
                    const voucherDiscountLine = document.getElementById('voucher-discount-line');
                    if (voucherDiscountLine) {
                        // Hiển thị theo loại mã: percentage hiển thị %, fixed hiển thị số tiền
                        voucherDiscountLine.textContent = data.display_text || (data.discount_type === 'percentage' ? `${data.discount_value}%` : `${numberFormat(data.discount_value)} ₫`);
                    }
                    
                    // "Giảm giá:" luôn hiển thị số tiền thực tế được giảm (tổng từ mã + điểm)
                    document.getElementById('discountDisplay').textContent = numberFormat(discount) + ' ₫';
                    
                    // Hiển thị thông tin chi tiết về mã giảm giá được áp dụng
                    let successMessage = `✅ ${data.message || 'Áp dụng mã giảm giá thành công'}`;
                    if (data.applied_code) {
                        successMessage += ` (Mã: ${data.applied_code})`;
                    }
                    
                    // Hiển thị thông tin rank và loại mã
                    if (data.promotion_rank && data.promotion_rank !== 'Chung') {
                        successMessage += ` - Mã VIP hạng ${data.promotion_rank}`;
                    } else {
                        successMessage += ` - Mã giảm giá chung`;
                    }
                    
                    if (data.user_rank && data.user_rank !== 'Khách thường') {
                        successMessage += ` (Hạng của bạn: ${data.user_rank})`;
                    }
                    
                    // Hiển thị thông tin trong payment step nếu có
                    const autoPromotionInfo = document.getElementById('auto-promotion-info');
                    if (autoPromotionInfo) {
                        let infoHtml = `<strong>🎉 Mã giảm giá đã áp dụng:</strong><br>`;
                        infoHtml += `<span style="font-weight: 600;">${data.promotion_code}</span>`;
                        
                        if (data.promotion_rank && data.promotion_rank !== 'Chung') {
                            infoHtml += `<span class="promotion-type-badge rank-badge">${data.promotion_rank}</span><br>`;
                            autoPromotionInfo.className = 'auto-promotion-info rank-promotion show';
                        } else {
                            infoHtml += `<span class="promotion-type-badge general-badge">CHUNG</span><br>`;
                            autoPromotionInfo.className = 'auto-promotion-info general-promotion show';
                        }
                        
                        // Hiển thị giảm giá theo đúng loại
                        if (data.discount_type === 'percentage') {
                            infoHtml += `<small>Giảm: <strong>${data.discount_value}%`;
                            if (data.max_discount) {
                                infoHtml += `, tối đa ${numberFormat(data.max_discount)} ₫`;
                            }
                            infoHtml += `</strong></small>`;
                        } else {
                            infoHtml += `<small>Giảm: <strong>${numberFormat(data.discount_value)} ₫</strong></small>`;
                        }
                        autoPromotionInfo.innerHTML = infoHtml;
                    }

                    // Cập nhật dòng "Giảm giá mã:" và "Giảm giá:" theo loại mã cho manual promotion
                    const voucherDiscountEl = document.getElementById('voucher-discount-line');
                    const discountDisplayEl = document.getElementById('discountDisplay');
                    
                    if (voucherDiscountEl) {
                        // Hiển thị theo loại mã: percentage hiển thị %, fixed hiển thị số tiền
                        voucherDiscountEl.textContent = data.display_text || (data.discount_type === 'percentage' ? `${data.discount_value}%` : `${numberFormat(data.discount_value)} ₫`);
                    }
                    
                    // "Giảm giá:" luôn hiển thị số tiền thực tế được giảm
                    if (discountDisplayEl) {
                        discountDisplayEl.textContent = `${numberFormat(maxDiscount)} ₫`;
                    }
                    
                    updateOrderSummary();
                    if (promotionFeedback) {
                        promotionFeedback.innerHTML = successMessage;
                        promotionFeedback.className = 'promotion-feedback success';
                    }
                    
                    // Lock input sau khi áp dụng thành công
                    const promotionCodeInput = document.getElementById('promotion-code-input');
                    const resetPromotionBtn = document.getElementById('reset-promotion-btn');
                    const applyPromotionBtn = document.getElementById('apply-promotion-btn');
                    
                    if (promotionCodeInput) {
                        promotionCodeInput.readOnly = true;
                        promotionCodeInput.classList.add('applied');
                        promotionCodeInput.style.background = 'linear-gradient(135deg, #1a4a37, #15542a)';
                        promotionCodeInput.style.borderColor = '#22c55e';
                        promotionCodeInput.style.color = '#fff';
                        promotionCodeInput.style.fontWeight = '600';
                        promotionCodeInput.style.boxShadow = '0 0 12px rgba(34, 197, 94, 0.3)';
                    }
                    
                    // Hiển thị nút đổi mã và ẩn nút áp dụng
                    if (resetPromotionBtn) resetPromotionBtn.style.display = 'block';
                    if (applyPromotionBtn) applyPromotionBtn.style.display = 'none';
                    
                    // Ẩn section mã giảm giá có sẵn sau khi áp dụng thành công
                    const availablePromotionsSection = document.getElementById('available-promotions-section');
                    if (availablePromotionsSection) {
                        availablePromotionsSection.style.display = 'none';
                    }
                    
                    // Ẩn các sub-sections
                    const rankPromotionsSection = document.getElementById('rank-promotions-section');
                    const generalPromotionsSection = document.getElementById('general-promotions-section');
                    const higherRankPromotionsSection = document.getElementById('higher-rank-promotions-section');
                    
                    if (rankPromotionsSection) rankPromotionsSection.style.display = 'none';
                    if (generalPromotionsSection) generalPromotionsSection.style.display = 'none';
                    if (higherRankPromotionsSection) higherRankPromotionsSection.style.display = 'none';
                    
                    console.log('Manual promotion applied successfully:', data.applied_code, 'Discount:', data.discount);
                } else {
                    discount = 0;
                    document.getElementById('voucher-discount-line').textContent = '0 ₫';
                    document.getElementById('discountDisplay').textContent = '0 ₫';
                    updateOrderSummary();
                    if (promotionFeedback) {
                        let errorMessage = data.error || 'Mã giảm giá không hợp lệ.';
                        promotionFeedback.innerHTML = errorMessage;
                        promotionFeedback.className = 'promotion-feedback error';
                    }
                }
            })
            .catch(error => {
                console.error('Error applying promotion:', error);
                console.error('Error type:', typeof error);
                console.error('Error keys:', Object.keys(error));
                
                // Kiểm tra nếu là lỗi từ server với data
                if (error.status && error.data) {
                    console.log('Server error data:', error.data);
                    if (promotionFeedback) {
                        let errorMessage = error.data.error || 'Không thể áp dụng mã giảm giá.';
                        
                        // Nếu có raw response (lỗi không phải JSON)
                        if (error.data.raw_response) {
                            console.log('Raw server response:', error.data.raw_response);
                            errorMessage = `Lỗi ${error.status}: Server trả về phản hồi không hợp lệ. Vui lòng thử lại.`;
                        }
                        
                        promotionFeedback.innerHTML = errorMessage;
                        promotionFeedback.className = 'promotion-feedback error';
                    }
                } else if (error.message) {
                    // Lỗi parsing JSON hoặc network
                    console.log('Network or parsing error:', error.message);
                    if (promotionFeedback) {
                        promotionFeedback.innerHTML = 'Lỗi kết nối máy chủ. Vui lòng thử lại.';
                        promotionFeedback.className = 'promotion-feedback error';
                    }
                } else {
                    // Lỗi khác
                    console.log('Unknown error:', error);
                    if (promotionFeedback) {
                        promotionFeedback.textContent = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
                        promotionFeedback.className = 'promotion-feedback error';
                    }
                }
                
                // Reset discount trong mọi trường hợp (chỉ reset promotion, giữ lại points)
                promotionDiscount = 0;
                discount = pointsDiscount; // Chỉ giữ lại discount từ điểm
                document.getElementById('voucher-discount-line').textContent = '0 ₫';
                document.getElementById('discountDisplay').textContent = numberFormat(discount) + ' ₫';
                updateOrderSummary();
            })
            .finally(() => {
                if (applyPromotionBtn) applyPromotionBtn.disabled = false;
            });
    }

    // Hàm áp dụng mã giảm giá thủ công
    function initPromotionButtons() {
        const applyPromotionBtn = document.getElementById('apply-promotion-btn');
        if (applyPromotionBtn) {
            applyPromotionBtn.onclick = function() {
                applyPromotion();
            };
            // Apply CSS classes
            applyPromotionBtn.className = 'promotion-btn promotion-apply-btn';
            console.log('Apply promotion button initialized');
        }

        // Hàm reset mã giảm giá để cho phép nhập mã mới
        const resetPromotionBtn = document.getElementById('reset-promotion-btn');
        if (resetPromotionBtn) {
            // Apply CSS class
            resetPromotionBtn.className = 'promotion-btn promotion-reset-btn';
            resetPromotionBtn.onclick = function() {
                console.log('Reset promotion button clicked');
                
                // Reset discount từ promotion, giữ lại points
                promotionDiscount = 0;
                discount = pointsDiscount; // Chỉ giữ lại discount từ điểm
                
                // Safely update discount display elements
                const voucherDiscountLine = document.getElementById('voucher-discount-line');
                const discountDisplay = document.getElementById('discountDisplay');
                
                if (voucherDiscountLine) {
                    voucherDiscountLine.textContent = '0 ₫';
                    console.log('Reset voucher-discount-line');
                } else {
                    console.warn('voucher-discount-line element not found');
                }
                
                if (discountDisplay) {
                    discountDisplay.textContent = numberFormat(discount) + ' ₫';
                    console.log('Reset discountDisplay');
                } else {
                    console.warn('discountDisplay element not found');
                }
                
                // Unlock input
                const promotionCodeInput = document.getElementById('promotion-code-input');
                if (promotionCodeInput) {
                    promotionCodeInput.readOnly = false;
                    promotionCodeInput.classList.remove('applied');
                    promotionCodeInput.style.background = '#1a1a1a';
                    promotionCodeInput.style.borderColor = '#444';
                    promotionCodeInput.style.color = '#fff';
                    promotionCodeInput.style.fontWeight = '500';
                    promotionCodeInput.style.boxShadow = 'none';
                    promotionCodeInput.value = '';
                    promotionCodeInput.placeholder = 'Nhập mã giảm giá';
                    setTimeout(() => {
                        promotionCodeInput.focus();
                    }, 100);
                    console.log('Unlocked promotion code input');
                } else {
                    console.warn('promotion-code-input element not found');
                }
                
                // Hiển thị lại nút áp dụng và ẩn nút đổi mã
                const applyPromotionBtn = document.getElementById('apply-promotion-btn');
                if (applyPromotionBtn) {
                    applyPromotionBtn.style.display = 'block';
                    applyPromotionBtn.disabled = false;
                    applyPromotionBtn.className = 'promotion-btn promotion-apply-btn';
                    console.log('Showed apply promotion button');
                }
                
                resetPromotionBtn.style.display = 'none';
                resetPromotionBtn.className = 'promotion-btn promotion-reset-btn';
                console.log('Hidden reset promotion button');
                
                // Reset feedback
                const promotionFeedback = document.getElementById('promotion-feedback');
                if (promotionFeedback) {
                    promotionFeedback.innerHTML = '';
                    promotionFeedback.className = 'promotion-feedback';
                }
                
                // Ẩn auto promotion info
                const autoPromotionInfo = document.getElementById('auto-promotion-info');
                if (autoPromotionInfo) {
                    autoPromotionInfo.classList.remove('show');
                    autoPromotionInfo.innerHTML = '';
                }
                
                // Hiển thị lại section mã giảm giá có sẵn
                const availablePromotionsSection = document.getElementById('available-promotions-section');
                if (availablePromotionsSection) {
                    availablePromotionsSection.style.display = 'block';
                    console.log('Showed available promotions section');
                }
                
                // Reset collapse state for all sections
                ['rank-promotions', 'general-promotions', 'higher-promotions'].forEach(prefix => {
                    const content = document.getElementById(`${prefix}-content`);
                    const icon = content?.previousElementSibling.querySelector('.promotion-toggle-icon');
                    if (content && content.classList.contains('collapsed')) {
                        content.classList.remove('collapsed');
                        if (icon) icon.classList.remove('collapsed');
                    }
                });
                
                // Reset search
                const globalSearch = document.getElementById('global-promotion-search');
                if (globalSearch) {
                    globalSearch.value = '';
                    searchQuery = '';
                }
                
                // Reset all promotion data pagination
                Object.keys(promotionData).forEach(type => {
                    promotionData[type].currentPage = 0;
                    promotionData[type].filtered = [...promotionData[type].all];
                });
                
                // Cập nhật lại tổng tiền
                updateOrderSummary();
                
                // Load lại gợi ý mã giảm giá sau một chút để đảm bảo DOM đã cập nhật
                setTimeout(() => {
                    showAvailablePromotionsSuggestion().then(() => {
                        console.log('Reloaded promotion suggestions after reset');
                    }).catch(error => {
                        console.error('Error reloading promotion suggestions after reset:', error);
                    });
                }, 200);
                
                console.log('Promotion reset completed successfully');
            };
            console.log('Reset promotion button initialized');
        }
    }

    // Hàm cập nhật tóm tắt đơn hàng
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
            subtotalDisplay: document.getElementById('subtotalDisplay'),
            discountDisplay: document.getElementById('discountDisplay'),
            totalDisplay: document.getElementById('totalDisplay'),
            summaryCinemaName: document.getElementById('summary-cinema-name'),
            snackItems: document.getElementById('snackItems'),
            snackTotal: document.getElementById('snack-total'),
            paymentMovieTitle: document.getElementById('payment-movie-title'),
            paymentCinemaName: document.getElementById('payment-cinema-name'),
            paymentShowtime: document.getElementById('payment-showtime'),
            paymentRoomName: document.getElementById('payment-room-name'),
            paymentSelectedSeats: document.getElementById('payment-selected-seats'),
            paymentTicketPrice: document.getElementById('payment-ticket-price'),
            paymentSnackTotal: document.getElementById('payment-snack-total'),
            paymentSummaryTotal: document.getElementById('paymentSummaryTotal'),
        };

        console.log('Selected seats before update:', JSON.stringify(selectedSeats));

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

        if (elements.summaryCinemaName) {
            elements.summaryCinemaName.textContent = cinemaName;
        } else {
            console.warn('Element summary-cinema-name not found in DOM');
        }

        const ticketCount = selectedSeats.length;
        const ticketTotal = selectedSeats.reduce((sum, seat) => sum + (seat.price || ticketPrice), 0);
        const subtotal = ticketTotal + (snackTotal || 0);
        
        // Đảm bảo discount không vượt quá subtotal và total không âm
        const validDiscount = Math.min(discount || 0, subtotal);
        const total = Math.max(0, subtotal - validDiscount);
        
        // Cập nhật lại discount nếu bị giới hạn
        if (validDiscount !== (discount || 0)) {
            console.log(`Discount adjusted from ${discount || 0} to ${validDiscount} to not exceed subtotal ${subtotal}`);
            discount = validDiscount;
        }
        
        console.log('Order Summary Update:', {
            ticketTotal,
            snackTotal: snackTotal || 0,
            subtotal,
            originalDiscount: discount,
            validDiscount,
            finalTotal: total,
            calculatedTotal: subtotal - validDiscount,
            MINIMUM_ORDER_AMOUNT: 200000
        });

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
                        elements[key].textContent = formattedTime && selectedDate ?
                            `${formattedTime} ${formatDateDisplay(selectedDate)}` : 'N/A';
                        break;
                    case 'discountAmount':
                        elements[key].textContent = numberFormat(validDiscount || 0) + ' ₫';
                        break;
                    case 'orderTotal':
                    case 'totalAmount':
                    case 'paymentSummaryTotal':
                    case 'totalDisplay':
                        elements[key].textContent = numberFormat(total) + ' ₫';
                        break;
                    case 'ticketScreen':
                    case 'ticketScreenDisplay':
                        elements[key].textContent = roomName;
                        break;
                    case 'ticketRow':
                    case 'ticketRowDisplay':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => (seat.label ||
                            'N/A').split('-')[0]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketSeat':
                    case 'ticketSeatDisplay':
                        elements[key].textContent = selectedSeats.length > 0 ? selectedSeats.map(seat => (seat.label ||
                            'N/A').split('-')[1]).join(', ') || 'N/A' : 'N/A';
                        break;
                    case 'ticketPriceDisplay':
                    case 'ticketPriceFinal':
                    case 'paymentTicketPrice':
                        elements[key].textContent = numberFormat(ticketTotal) + ' ₫';
                        break;
                    case 'ticketDate':
                    case 'ticketDateDisplay':
                        elements[key].textContent = selectedDate || 'N/A';
                        break;
                    case 'ticketTime':
                    case 'ticketTimeDisplay':
                        elements[key].textContent = selectedTime || 'N/A';
                        break;
                    case 'movieTitle':
                    case 'paymentMovieTitle':
                        elements[key].textContent = movieTitle || 'N/A';
                        break;
                    case 'cinemaName':
                    case 'paymentCinemaName':
                        elements[key].textContent = cinemaName;
                        break;
                    case 'roomName':
                    case 'paymentRoomName':
                        elements[key].textContent = roomName;
                        break;
                    case 'paymentSelectedSeats':
                        elements[key].textContent = selectedSeats.map(seat => seat.label || 'N/A').join(', ') || 'N/A';
                        break;
                    case 'paymentSnackTotal':
                        elements[key].textContent = numberFormat(snackTotal || 0) + ' ₫';
                        break;
                    case 'subtotalDisplay':
                        elements[key].textContent = numberFormat(ticketTotal + (snackTotal || 0)) + ' ₫';
                        break;
                    case 'discountDisplay':
                        elements[key].textContent = numberFormat(validDiscount || 0) + ' ₫';
                        break;
                    case 'snackItems':
                        elements[key].textContent = (snackTotal || 0) > 0 ? (document.querySelectorAll(
                            '#summary-table-body tr[data-variant-key]').length > 0 ? Array.from(document
                            .querySelectorAll('#summary-table-body tr[data-variant-key]')).map(row => row
                            .children[0].textContent).join(', ') : 'Chưa chọn đồ ăn') : 'Chưa chọn đồ ăn';
                        break;
                    case 'snackTotal':
                        elements[key].textContent = numberFormat(snackTotal || 0) + ' ₫';
                        break;
                }
            } else {
                console.warn(`Element ${key} not found in DOM`);
            }
        }

        const summaryTotalElement = document.getElementById('summary-total');
        if (summaryTotalElement) {
            summaryTotalElement.textContent = numberFormat(total) + ' ₫';
        }
        
        // Kiểm tra điều kiện đơn hàng tối thiểu
        if (currentStep === 4) {
            setTimeout(() => {
                checkMinimumOrderAmount();
            }, 100);
        }
    }

    // Hàm xử lý bước tiếp theo
    function handleNextStep(e) {
        e.preventDefault();
        if (currentStep === 1 && !selectedShowtimeId) {
            alert('Vui lòng chọn suất chiếu trước khi tiếp tục.');
            return;
        }
        if (currentStep < 5) {
            currentStep++;
            showStep(currentStep);
            updateOrderSummary();
        }
    }

    // Hàm xử lý bước quay lại
    function handlePreviousStep(e) {
        e.preventDefault();
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    // Hàm hiển thị bước
    function showStep(step) {
        document.querySelectorAll('fieldset').forEach((fieldset, index) => {
            fieldset.style.display = index === step - 1 ? 'block' : 'none';
        });
        document.querySelectorAll('#progressbar li').forEach((li, index) => {
            li.classList.remove('active');
            if (index < step) li.classList.add('active');
        });

        if (step === 1) {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                ['hours-snack', 'minutes-snack', 'seconds-snack', 'hours-payment', 'minutes-payment', 'seconds-payment']
                .forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = "00";
                });
                countdownEndTime = null;
            }
            
            // Dừng auto refresh khi không ở payment step
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
                console.log('Stopped auto refresh promotion data');
            }
        } else if (step === 3 || step === 4) {
            if (!countdownInterval && selectedShowtimeId) {
                startCountdown(600);
            }
        }

        if (step === 4) {
            // Tắt tự động áp dụng mã giảm giá - để user tự nhập
            // applyPromotionAutomatically();
            
            // Khởi tạo lại các nút promotion để đảm bảo chúng hoạt động
            setTimeout(() => {
                initPromotionButtons();
            }, 100);
            
            // Hiển thị gợi ý mã giảm giá có sẵn cho user
            showAvailablePromotionsSuggestion().then(() => {
                console.log('Promotion suggestions loaded successfully');
                
                // Hiển thị thông báo về tính năng mới (chỉ hiển thị 1 lần)
                if (!localStorage.getItem('refreshPromotionTipShown')) {
                    setTimeout(() => {
                        const featureTip = document.createElement('div');
                        featureTip.className = 'feature-tip';
                        featureTip.style.cssText = `
                            position: fixed;
                            bottom: 20px;
                            right: 20px;
                            background: #1e40af;
                            color: white;
                            padding: 14px;
                            border-radius: 8px;
                            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
                            z-index: 10000;
                            font-size: 14px;
                            max-width: 300px;
                            line-height: 1.4;
                            opacity: 0;
                            transform: translateY(20px);
                            transition: all 0.3s ease;
                        `;
                        
                        featureTip.innerHTML = `
                            <div style="display: flex; align-items: flex-start;">
                                <div style="flex: 1;">
                                    <div style="font-weight: bold; margin-bottom: 4px;">✨ Tính năng mới!</div>
                                    <p style="margin: 0 0 8px 0;">Danh sách mã giảm giá sẽ tự động cập nhật mỗi 30 giây để hiển thị mã mới nhất.</p>
                                    <p style="margin: 0 0 8px 0;">Bạn cũng có thể nhấn nút <b>Làm mới</b> hoặc phím <b>F5</b> để cập nhật ngay lập tức.</p>
                                    <button onclick="this.parentNode.parentNode.style.opacity='0'; setTimeout(() => this.parentNode.parentNode.remove(), 300); localStorage.setItem('refreshPromotionTipShown', 'true');" style="background: white; color: #1e40af; border: none; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">Đã hiểu</button>
                                </div>
                                <div style="margin-left: 12px; cursor: pointer;" onclick="this.parentNode.parentNode.style.opacity='0'; setTimeout(() => this.parentNode.parentNode.remove(), 300); localStorage.setItem('refreshPromotionTipShown', 'true');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(featureTip);
                        
                        setTimeout(() => {
                            featureTip.style.opacity = '1';
                            featureTip.style.transform = 'translateY(0)';
                        }, 100);
                        
                        // Auto hide after 12 seconds
                        setTimeout(() => {
                            featureTip.style.opacity = '0';
                            featureTip.style.transform = 'translateY(20px)';
                            setTimeout(() => {
                                if (featureTip.parentNode) {
                                    featureTip.parentNode.removeChild(featureTip);
                                }
                            }, 300);
                            localStorage.setItem('refreshPromotionTipShown', 'true');
                        }, 12000);
                    }, 2000);
                }
            }).catch(error => {
                console.error('Error loading promotion suggestions:', error);
            });
            
            // Bắt đầu auto refresh promotion data mỗi 30 giây
            if (!autoRefreshInterval) {
                autoRefreshInterval = setInterval(() => {
                    // Chỉ refresh nếu user không đang thao tác với promotion
                    const promotionInput = document.getElementById('promotion-code-input');
                    const searchInput = document.getElementById('global-promotion-search');
                    const refreshBtn = document.getElementById('refresh-promotions-btn');
                    
                    const isUserInteracting = promotionInput && promotionInput === document.activeElement ||
                                            searchInput && searchInput === document.activeElement ||
                                            refreshBtn && refreshBtn.disabled;
                    
                    if (!isUserInteracting && currentStep === 4) {
                        console.log('Auto refreshing promotion data...');
                        showAvailablePromotionsSuggestion().then(() => {
                            console.log('Auto refresh completed');
                        }).catch(error => {
                            console.error('Auto refresh failed:', error);
                        });
                    }
                }, 30000); // 30 seconds
                console.log('Started auto refresh promotion data every 30 seconds');
            }

            const elMovieTitle = document.getElementById('payment-movie-title');
            if (elMovieTitle) elMovieTitle.textContent = movieTitle || 'N/A';

            const elCinemaName = document.getElementById('payment-cinema-name');
            if (elCinemaName) elCinemaName.textContent = cinemaName || 'N/A';

            const elShowtime = document.getElementById('payment-showtime');
            if (elShowtime) elShowtime.textContent = selectedTime && selectedDate ?
                `${selectedTime} ${formatDateDisplay(selectedDate)}` : 'N/A';

            const elRoomName = document.getElementById('payment-room-name');
            if (elRoomName) elRoomName.textContent = document.getElementById('room-name')?.textContent || 'N/A';

            const elSelectedSeats = document.getElementById('payment-selected-seats');
            if (elSelectedSeats) elSelectedSeats.textContent = selectedSeats.map(seat => seat.label || 'N/A').join(
                ', ') || 'N/A';

            const elTicketPrice = document.getElementById('payment-ticket-price');
            if (elTicketPrice) elTicketPrice.textContent = document.getElementById('ticket-price')?.textContent ||
                '0 ₫';

            const snackTableBody = document.querySelector('#payment-snack-table tbody');
            if (snackTableBody) {
                snackTableBody.innerHTML = '';
                document.querySelectorAll('#summary-table-body tr[data-variant-key]').forEach(row => {
                    snackTableBody.appendChild(row.cloneNode(true));
                });
            }
            const elSnackTotal = document.getElementById('payment-snack-total');
            if (elSnackTotal) elSnackTotal.textContent = document.getElementById('snack-total')?.textContent || '0 ₫';

            const elPaymentSummaryTotal = document.getElementById('paymentSummaryTotal');
            if (elPaymentSummaryTotal) {
                const totalDisplay = document.getElementById('totalDisplay');
                elPaymentSummaryTotal.textContent = totalDisplay ? totalDisplay.textContent : '0 ₫';
            }
        }

        console.log('Displaying step:', step);
        updateOrderSummary();
    }

    // Hàm hiển thị gợi ý mã giảm giá có sẵn cho user
    function showAvailablePromotionsSuggestion() {
        const total = parseInt(document.getElementById('totalDisplay').textContent.replace(/[^\d]/g, '')) || 0;
        
        if (total <= 0) {
            console.log('Total is 0, no promotion suggestions needed');
            return Promise.resolve();
        }

        return fetch('{{ route('client.getAvailablePromotions') }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('[DEBUG] Raw backend response:', data);
            
            if (data.success && data.data) {
                console.log('[DEBUG] Available promotions from backend:', data.data);
                console.log('[DEBUG] Backend data structure:', {
                    user_rank: data.data.user_rank,
                    general: data.data.general, 
                    higher_ranks: data.data.higher_ranks,
                    success: data.success
                });
                
                // Lấy thông tin user và ranks từ backend
                const userRank = data.user_rank || 'Khách thường';
                const userRankId = data.user_rank_id;
                const allRanks = data.all_ranks || [];
                
                console.log('[DEBUG] User rank info:', {userRank, userRankId});
                console.log('[DEBUG] All ranks from DB:', allRanks);
                
                // Sử dụng dữ liệu đã được phân loại từ backend
                // Ensure higher_ranks is always an array even if it's null/undefined
                const higherRanks = data.data.higher_ranks || [];
                console.log('[DEBUG] Higher ranks data before processing:', higherRanks);
                
                promotionData = {
                    rank: { 
                        all: data.data.user_rank || [], 
                        displayed: [], 
                        filtered: data.data.user_rank || [], 
                        pageSize: 6, 
                        currentPage: 0 
                    },
                    general: { 
                        all: data.data.general || [], 
                        displayed: [], 
                        filtered: data.data.general || [], 
                        pageSize: 6, 
                        currentPage: 0 
                    },
                    higher: { 
                        all: higherRanks, 
                        displayed: [], 
                        filtered: higherRanks, 
                        pageSize: 6, 
                        currentPage: 0 
                    }
                };
                
                console.log('[DEBUG] Higher ranks after assignment:', {
                    count: promotionData.higher.all.length,
                    data: promotionData.higher.all
                });
                
                console.log('[DEBUG] Final promotion data structure after processing:', {
                    rank: {
                        count: promotionData.rank.all.length,
                        codes: promotionData.rank.all.map(p => p.code),
                        data: promotionData.rank.all
                    },
                    general: {
                        count: promotionData.general.all.length,
                        codes: promotionData.general.all.map(p => p.code),
                        data: promotionData.general.all
                    },
                    higher: {
                        count: promotionData.higher.all.length,
                        codes: promotionData.higher.all.map(p => p.code),
                        data: promotionData.higher.all
                    }
                });
                
                // Force hiển thị sections nếu có dữ liệu
                const hasRankPromotions = promotionData.rank.all.length > 0;
                const hasGeneralPromotions = promotionData.general.all.length > 0;
                const hasHigherPromotions = promotionData.higher.all.length > 0;
                
                console.log('[DEBUG] Sections visibility check:', {
                    hasRankPromotions,
                    hasGeneralPromotions, 
                    hasHigherPromotions
                });
                
                // Hiển thị từng section riêng biệt và update count
                if (hasRankPromotions) {
                    const rankSection = document.getElementById('rank-promotions-section');
                    const rankCount = document.getElementById('rank-promotions-count');
                    if (rankSection) {
                        rankSection.style.display = 'block';
                        console.log('[DEBUG] Showed rank promotions section with', promotionData.rank.all.length, 'promotions');
                        console.log('[DEBUG] Rank section current style:', rankSection.style.display, rankSection.offsetHeight);
                    } else {
                        console.error('[DEBUG] rank-promotions-section element not found!');
                    }
                    if (rankCount) {
                        rankCount.textContent = promotionData.rank.all.length;
                    } else {
                        console.error('[DEBUG] rank-promotions-count element not found!');
                    }
                } else if (userRank && userRank !== 'Khách thường') {
                    // Hiển thị thông báo rằng user có rank nhưng không có mã giảm giá cho rank đó
                    console.log('[DEBUG] User has rank but no promotions available for this rank');
                    const rankSection = document.getElementById('rank-promotions-section');
                    if (rankSection) {
                        rankSection.style.display = 'block';
                        const rankList = document.getElementById('rank-promotions-list');
                        if (rankList) {
                            rankList.innerHTML = `
                                <div class="promotion-empty-state" style="padding: 20px; text-align: center; color: #6b7280; background: rgba(107, 114, 128, 0.1); border-radius: 8px; margin: 10px 0;">
                                    <div style="font-size: 24px; margin-bottom: 8px;">🎫</div>
                                    <p style="margin: 0; font-size: 14px;">Hiện tại chưa có mã giảm giá dành riêng cho hạng ${userRank}</p>
                                    <p style="margin: 4px 0 0 0; font-size: 12px; opacity: 0.8;">Hãy xem các mã giảm giá chung bên dưới!</p>
                                </div>
                            `;
                        }
                        const rankCount = document.getElementById('rank-promotions-count');
                        if (rankCount) {
                            rankCount.textContent = '0';
                        }
                    }
                }
                
                if (hasGeneralPromotions) {
                    const generalSection = document.getElementById('general-promotions-section');
                    const generalCount = document.getElementById('general-promotions-count');
                    if (generalSection) {
                        generalSection.style.display = 'block';
                        console.log('[DEBUG] Showed general promotions section with', promotionData.general.all.length, 'promotions');
                        console.log('[DEBUG] General promotions data:', promotionData.general.all.map(p => ({
                            code: p.code,
                            name: p.name,
                            rank_id: p.rank_id,
                            type: p.type
                        })));
                    }
                    if (generalCount) {
                        generalCount.textContent = promotionData.general.all.length;
                    }
                }
                
                if (hasHigherPromotions) {
                    const higherSection = document.getElementById('higher-rank-promotions-section');
                    const higherCount = document.getElementById('higher-rank-promotions-count');
                    if (higherSection) {
                        higherSection.style.display = 'block';
                        console.log('[DEBUG] Showed higher rank promotions section with', promotionData.higher.all.length, 'promotions');
                        console.log('[DEBUG] Higher rank promotion data:', promotionData.higher.all);
                    }
                    if (higherCount) {
                        higherCount.textContent = promotionData.higher.all.length;
                        console.log('[DEBUG] Updated higher rank count to', promotionData.higher.all.length);
                    } else {
                        console.error('[DEBUG] higher-promotions-count element not found - this may cause display issues');
                    }
                }
                
                // Hiển thị sections và load first page
                console.log('[DEBUG] About to call renderAllPromotionSections');
                renderAllPromotionSections();
                console.log('[DEBUG] Called renderAllPromotionSections');
                updatePromotionStats();
                
                // Clear search when new data loads
                const globalSearch = document.getElementById('global-promotion-search');
                if (globalSearch) {
                    globalSearch.value = '';
                    searchQuery = '';
                }
                
                // Hiển thị section chính nếu có mã nào đó
                const availablePromotionsSection = document.getElementById('available-promotions-section');
                if (promotionData.rank.all.length > 0 || promotionData.general.all.length > 0 || promotionData.higher.all.length > 0) {
                    availablePromotionsSection.style.display = 'block';
                }
                
                // Hiển thị thông tin gợi ý
                const promotionFeedback = document.getElementById('promotion-feedback');
                if (promotionFeedback) {
                    let suggestionText = '';
                    
                    if (promotionData.rank.all.length > 0) {
                        suggestionText = `🏆 Bạn có ${promotionData.rank.all.length} mã VIP dành cho hạng ${userRank}.`;
                    } else if (userRank && userRank !== 'Khách thường') {
                        suggestionText = `💎 Chưa có mã VIP cho hạng ${userRank}.`;
                    }
                    
                    if (promotionData.general.all.length > 0) {
                        if (suggestionText) suggestionText += ' ';
                        suggestionText += `🎁 Có ${promotionData.general.all.length} mã giảm giá chung.`;
                    }
                    
                    if (promotionData.higher.all.length > 0) {
                        if (suggestionText) suggestionText += ' ';
                        suggestionText += `✨ Có ${promotionData.higher.all.length} mã hạng cao hơn.`;
                        console.log('[DEBUG] Higher rank promotions for suggestion text:', promotionData.higher.all.length);
                        console.log('[DEBUG] Higher rank promotion codes:', promotionData.higher.all.map(p => p.code));
                    }
                    
                    if (suggestionText) {
                        suggestionText += ' Click vào mã để áp dụng.';
                        promotionFeedback.innerHTML = suggestionText;
                        promotionFeedback.className = 'promotion-feedback info';
                    }
                }
                
                // Cập nhật placeholder cho input
                const promotionCodeInput = document.getElementById('promotion-code-input');
                if (promotionCodeInput && !promotionCodeInput.readOnly) {
                    if (promotionData.rank.all.length > 0) {
                        promotionCodeInput.placeholder = `💎 Nhập mã ${userRank} hoặc click chọn mã phía trên`;
                    } else if (promotionData.general.all.length > 0) {
                        promotionCodeInput.placeholder = `🎁 Nhập mã giảm giá hoặc click chọn mã phía trên`;
                    } else {
                        promotionCodeInput.placeholder = `📝 Nhập mã giảm giá`;
                    }
                    
                    // Apply improved styling
                    promotionCodeInput.className = 'promotion-code-input';
                }
            } else {
                console.log('No promotions available for suggestion');
                
                // Reset promotion data
                promotionData = {
                    rank: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 },
                    general: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 },
                    higher: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 }
                };
                
                const promotionFeedback = document.getElementById('promotion-feedback');
                if (promotionFeedback) {
                    promotionFeedback.innerHTML = '<small style="color: #6b7280;">Hiện tại không có mã giảm giá khả dụng</small>';
                }
                
                // Ẩn section mã giảm giá
                const availablePromotionsSection = document.getElementById('available-promotions-section');
                if (availablePromotionsSection) {
                    availablePromotionsSection.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error loading promotion suggestions:', error);
            throw error; // Re-throw để refreshPromotionData có thể catch
        });
    }

    // Function to get rank-specific CSS class (sử dụng dữ liệu động)
    function getRankCssClass(rank) {
        if (!rank) return 'dong';
        
        // Chuyển đổi tên rank thành class CSS
        const cssClass = rank.toLowerCase()
            .replace(/\s+/g, '-')  // Thay space bằng dấu gạch ngang
            .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
            .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
            .replace(/[ìíịỉĩ]/g, 'i')
            .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
            .replace(/[ùúụủũưừứựửữ]/g, 'u')
            .replace(/[ỳýỵỷỹ]/g, 'y')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9-]/g, ''); // Loại bỏ ký tự đặc biệt
        
        return cssClass || 'dong';
    }
    
    // Function to get rank icon (sử dụng dữ liệu động)
    function getRankIcon(rank) {
        if (!rank) return '🎫';
        
        // Mapping icon theo tên rank (có thể customize thêm)
        const iconMap = {
            'kim cương': '💎',
            'kim-cương': '💎',
            'bạch kim': '🤍',
            'bach-kim': '🤍',
            'vàng': '🥇',
            'vang': '🥇',
            'bạc': '🥈',
            'bac': '🥈',
            'đồng': '🥉',
            'dong': '🥉'
        };
        
        const normalizedRank = rank.toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
            .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
            .replace(/[ìíịỉĩ]/g, 'i')
            .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
            .replace(/[ùúụủũưừứựửữ]/g, 'u')
            .replace(/[ỳýỵỷỹ]/g, 'y')
            .replace(/đ/g, 'd');
        
        return iconMap[normalizedRank] || iconMap[rank.toLowerCase()] || '🎫';
    }

    // Hàm render promotion section với lazy loading
    function renderPromotionSection(type, userRank = '') {
        console.log(`[DEBUG] renderPromotionSection called for type: ${type}`);
        
        const data = promotionData[type];
        const sourceData = searchQuery ? data.filtered : data.all;
        
        console.log(`[DEBUG] renderPromotionSection - ${type}:`, {
            totalData: data.all.length,
            filteredData: data.filtered.length,
            sourceDataLength: sourceData.length,
            searchQuery: searchQuery
        });
        
        if (sourceData.length === 0) {
            console.log(`[DEBUG] No data for ${type}, hiding section`);
            const sectionId = type === 'rank' ? 'rank-promotions-section' : 
                             type === 'general' ? 'general-promotions-section' : 
                             'higher-rank-promotions-section';
            const section = document.getElementById(sectionId);
            if (section) section.style.display = 'none';
            return;
        }
        
        const sectionId = type === 'rank' ? 'rank-promotions-section' : 
                         type === 'general' ? 'general-promotions-section' : 
                         'higher-rank-promotions-section';
        const listId = type === 'rank' ? 'rank-promotions-list' : 
                      type === 'general' ? 'general-promotions-list' : 
                      'higher-rank-promotions-list';
        const countId = type === 'rank' ? 'rank-promotions-count' : 
                       type === 'general' ? 'general-promotions-count' : 
                       'higher-rank-promotions-count';
        const showMoreId = type === 'higher' ? 'higher-rank-show-more' : `${type}-show-more`;
        
        console.log(`[DEBUG] renderPromotionSection - ${type} IDs:`, {
            sectionId, listId, countId, showMoreId
        });
        
        // Debug: kiểm tra tồn tại của các elements
        const section = document.getElementById(sectionId);
        const listElement = document.getElementById(listId);
        const countBadge = document.getElementById(countId);
        
        console.log(`[DEBUG] Elements check for ${type}:`, {
            section: !!section,
            listElement: !!listElement, 
            countBadge: !!countBadge,
            sectionDisplay: section?.style.display,
            sectionVisible: section?.offsetHeight > 0
        });
        
        // Hiển thị section
        if (section) {
            section.style.display = 'block';
            console.log(`[DEBUG] Showed section ${sectionId}, new display:`, section.style.display);
            console.log(`[DEBUG] Section visibility after show:`, section.offsetHeight, section.offsetWidth);
        } else {
            console.log(`[DEBUG] Section ${sectionId} not found!`);
        }
        
        // Cập nhật count badge
        if (countBadge) {
            countBadge.textContent = sourceData.length;
            console.log(`[DEBUG] Updated count badge ${countId} to ${sourceData.length}`);
        } else {
            console.log(`[DEBUG] Count badge ${countId} not found!`);
        }
        
        // Clear và load first page
        if (listElement) {
            listElement.innerHTML = '';
            console.log(`[DEBUG] Cleared list ${listId}`);
            
            // Batch render để tối ưu hiệu năng
            const firstPagePromotions = sourceData.slice(0, data.pageSize);
            const fragment = document.createDocumentFragment();
            
            console.log(`[DEBUG] Rendering ${firstPagePromotions.length} promotions for ${type}`);
            firstPagePromotions.forEach((promotion, index) => {
                console.log(`[DEBUG] Rendering promotion ${index + 1}:`, {
                    code: promotion.code,
                    name: promotion.name,
                    rank: promotion.rank,
                    discount_type: promotion.discount_type,
                    discount_value: promotion.discount_value,
                    category: promotion.category
                });
                const button = createPromotionButton(promotion, type);
                if (button) {
                    fragment.appendChild(button);
                } else {
                    console.error(`[DEBUG] Failed to create button for promotion:`, promotion);
                }
            });
            
            listElement.appendChild(fragment);
            console.log(`[DEBUG] Added ${firstPagePromotions.length} promotion buttons to ${listId}`);
            
            // Hiển thị nút "Xem thêm" nếu cần
            const showMoreBtn = document.getElementById(showMoreId);
            if (showMoreBtn && sourceData.length > data.pageSize) {
                showMoreBtn.style.display = 'block';
                console.log(`[DEBUG] Showed 'show more' button for ${type}`);
            } else if (showMoreBtn) {
                showMoreBtn.style.display = 'none';
                console.log(`[DEBUG] Hid 'show more' button for ${type}`);
            }
        } else {
            console.log(`[DEBUG] List element ${listId} not found!`);
        }
        
        console.log(`[DEBUG] Rendered ${type} promotions: ${sourceData.length} total, showing first ${Math.min(data.pageSize, sourceData.length)}`);
    }

    // Hàm update search results indicator
    function updateSearchResults(query) {
        const indicator = document.getElementById('search-results-indicator');
        if (!indicator) return;
        
        if (query.trim() === '') {
            indicator.classList.remove('show');
            return;
        }
        
        // Đếm kết quả tìm kiếm
        let totalResults = 0;
        Object.values(promotionData).forEach(data => {
            const filtered = data.all.filter(promotion => {
                return promotion.code.toLowerCase().includes(query.toLowerCase()) || 
                       (promotion.name && promotion.name.toLowerCase().includes(query.toLowerCase())) ||
                       (promotion.rank && promotion.rank.toLowerCase().includes(query.toLowerCase())) ||
                       (promotion.type && promotion.type.toLowerCase().includes(query.toLowerCase()));
            });
            totalResults += filtered.length;
        });
        
        if (totalResults > 0) {
            indicator.textContent = totalResults;
            indicator.classList.add('show');
            indicator.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
        } else {
            indicator.textContent = '0';
            indicator.classList.add('show');
            indicator.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        }
    }
    
    // Hàm tìm kiếm mã giảm giá
    function searchPromotions(query) {
        searchQuery = query.toLowerCase().trim();
        console.log('Searching promotions with query:', searchQuery);
        
        // Reset tất cả filtered arrays
        Object.keys(promotionData).forEach(type => {
            promotionData[type].filtered = [];
            promotionData[type].currentPage = 0;
        });
        
        if (searchQuery === '') {
            // Nếu không có query, hiển thị tất cả
            Object.keys(promotionData).forEach(type => {
                promotionData[type].filtered = [...promotionData[type].all];
            });
        } else {
            // Filter theo query
            Object.keys(promotionData).forEach(type => {
                promotionData[type].filtered = promotionData[type].all.filter(promotion => {
                    return promotion.code.toLowerCase().includes(searchQuery) || 
                           (promotion.name && promotion.name.toLowerCase().includes(searchQuery)) ||
                           (promotion.rank && promotion.rank.toLowerCase().includes(searchQuery)) ||
                           (promotion.type && promotion.type.toLowerCase().includes(searchQuery));
                });
            });
        }
        
        // Re-render tất cả sections
        renderAllPromotionSections();
        updatePromotionStats();
        
        // Hiển thị no results nếu không tìm thấy gì
        const totalResults = Object.values(promotionData).reduce((sum, data) => sum + data.filtered.length, 0);
        const noResultsDiv = document.getElementById('promotion-no-results');
        if (noResultsDiv) {
            noResultsDiv.style.display = totalResults === 0 && searchQuery !== '' ? 'block' : 'none';
        }
    }
    
    // Hàm update promotion statistics
    function updatePromotionStats() {
        const statsElement = document.getElementById('promotion-stats');
        if (!statsElement) return;
        
        const totalAll = Object.values(promotionData).reduce((sum, data) => sum + data.all.length, 0);
        const totalFiltered = Object.values(promotionData).reduce((sum, data) => sum + data.filtered.length, 0);
        
        if (searchQuery) {
            statsElement.innerHTML = `📊 Hiển thị ${totalFiltered}/${totalAll} mã giảm giá`;
            statsElement.style.display = 'block';
        } else {
            statsElement.innerHTML = `📊 Tổng cộng ${totalAll} mã giảm giá khả dụng`;
            statsElement.style.display = totalAll > 0 ? 'block' : 'none';
        }
    }
    
    // Hàm render tất cả promotion sections
    function renderAllPromotionSections() {
        console.log('[DEBUG] renderAllPromotionSections called');
        console.log('[DEBUG] Current promotionData state:', {
            rank: promotionData.rank?.all?.length || 0,
            general: promotionData.general?.all?.length || 0,
            higher: promotionData.higher?.all?.length || 0
        });
        
        // Check if higher rank element exists before rendering
        const higherRankSection = document.getElementById('higher-rank-promotions-section');
        const higherRankList = document.getElementById('higher-rank-promotions-list');
        const higherRankCount = document.getElementById('higher-rank-promotions-count');
        
        console.log('[DEBUG] Higher rank elements check:', {
            section: !!higherRankSection,
            list: !!higherRankList,
            count: !!higherRankCount,
            higherRankData: promotionData.higher?.all || []
        });
        
        ['rank', 'general', 'higher'].forEach(type => {
            console.log(`[DEBUG] About to render section: ${type}`);
            renderPromotionSection(type, type === 'rank' ? 'Hạng của bạn' : '');
        });
    }

    // Hàm reset mã giảm giá khi không đủ điều kiện
    function resetPromotionState(reason = '') {
        console.log('Resetting promotion state:', reason);
        
        // Reset discount từ mã khuyến mãi
        promotionDiscount = 0;
        discount = pointsDiscount; // Giữ lại discount từ điểm
        
        // Reset display elements
        const voucherDiscountLine = document.getElementById('voucher-discount-line');
        const discountDisplay = document.getElementById('discountDisplay');
        
        if (voucherDiscountLine) voucherDiscountLine.textContent = '0 ₫';
        if (discountDisplay) discountDisplay.textContent = numberFormat(discount) + ' ₫';
        
        // Reset input
        const promotionCodeInput = document.getElementById('promotion-code-input');
        if (promotionCodeInput) {
            promotionCodeInput.value = '';
            promotionCodeInput.readOnly = false;
            promotionCodeInput.classList.remove('applied');
            promotionCodeInput.style.background = '#1a1a1a';
            promotionCodeInput.style.borderColor = '#444';
            promotionCodeInput.style.color = '#fff';
            promotionCodeInput.style.fontWeight = '500';
            promotionCodeInput.style.boxShadow = 'none';
        }
        
        // Reset buttons
        const applyPromotionBtn = document.getElementById('apply-promotion-btn');
        const resetPromotionBtn = document.getElementById('reset-promotion-btn');
        
        if (applyPromotionBtn) {
            applyPromotionBtn.style.display = 'block';
            applyPromotionBtn.disabled = false;
        }
        if (resetPromotionBtn) {
            resetPromotionBtn.style.display = 'none';
        }
        
        // Reset auto promotion info
        const autoPromotionInfo = document.getElementById('auto-promotion-info');
        if (autoPromotionInfo) {
            autoPromotionInfo.classList.remove('show');
            autoPromotionInfo.innerHTML = '';
        }
        
        // Show available promotions section if hidden
        const availablePromotionsSection = document.getElementById('available-promotions-section');
        if (availablePromotionsSection && currentStep === 4) {
            availablePromotionsSection.style.display = 'block';
        }
    }

    // Hàm kiểm tra và hiển thị thông báo đơn hàng tối thiểu
    function checkMinimumOrderAmount() {
        const subtotalElement = document.getElementById('subtotalDisplay');
        const subtotalText = subtotalElement ? subtotalElement.textContent : '0 ₫';
        const subtotal = parseInt(subtotalText.replace(/[^\d]/g, '')) || 0;
        
        const MINIMUM_ORDER_AMOUNT = 200000;
        const promotionFeedback = document.getElementById('promotion-feedback');
        
        if (!promotionFeedback) return;
        
        // Nếu chưa đủ điều kiện và đang có mã giảm giá được áp dụng
        if (subtotal < MINIMUM_ORDER_AMOUNT) {
            // Reset mã giảm giá nếu đang có (chỉ reset promotion, giữ lại points)
            if (promotionDiscount > 0) {
                resetPromotionState(`Order subtotal ${subtotal} below minimum ${MINIMUM_ORDER_AMOUNT}`);
            }
            
            const remaining = MINIMUM_ORDER_AMOUNT - subtotal;
            const percentage = (subtotal / MINIMUM_ORDER_AMOUNT * 100).toFixed(1);
            
            if (subtotal > 0) {
                promotionFeedback.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span>📊 Tiến độ đơn hàng:</span>
                        <div style="flex: 1; background: rgba(255,255,255,0.1); border-radius: 10px; height: 6px; overflow: hidden;">
                            <div style="width: ${percentage}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #1d4ed8); transition: width 0.3s ease;"></div>
                        </div>
                        <span style="font-size: 0.8em;">${percentage}%</span>
                    </div>
                    <small>💡 Còn ${numberFormat(remaining)} ₫ nữa để sử dụng mã giảm giá (tối thiểu ${numberFormat(MINIMUM_ORDER_AMOUNT)} ₫)</small>
                `;
                promotionFeedback.className = 'promotion-feedback info';
            } else {
                promotionFeedback.innerHTML = '';
                promotionFeedback.className = 'promotion-feedback';
            }
        } else {
            // Đủ điều kiện - chỉ hiển thị thông báo tích cực nếu:
            // 1. Chưa có mã nào được áp dụng (discount === 0)
            // 2. Không có thông báo hiện tại (hoặc chỉ là thông báo trống)
            // 3. Không có thông báo lỗi từ việc áp dụng mã
            // 4. Không có thông báo thành công từ việc áp dụng mã
            const hasNoCurrentMessage = !promotionFeedback.innerHTML.trim() || 
                                      promotionFeedback.innerHTML === '';
            const hasNoErrorOrSuccessMessage = !promotionFeedback.className.includes('error') && 
                                             !promotionFeedback.className.includes('warning') &&
                                             !promotionFeedback.className.includes('success');
            
            if (promotionDiscount === 0 && hasNoCurrentMessage && hasNoErrorOrSuccessMessage) {
                promotionFeedback.innerHTML = `✅ Đơn hàng của bạn đã đủ điều kiện sử dụng mã giảm giá!`;
                promotionFeedback.className = 'promotion-feedback success';
            }
        }
    }
    function showSkeletonLoading(listId, count = 3) {
        const listElement = document.getElementById(listId);
        if (!listElement) return;
        
        for (let i = 0; i < count; i++) {
            const skeleton = document.createElement('div');
            skeleton.className = 'promotion-skeleton';
            listElement.appendChild(skeleton);
        }
    }
    
    // Hàm chọn mã giảm giá từ gợi ý
    function selectPromotionCode(code) {
        const promotionCodeInput = document.getElementById('promotion-code-input');
        if (promotionCodeInput) {
            promotionCodeInput.value = code;
            // Tự động áp dụng mã khi user click chọn
            applyPromotion();
        }
    }

    // Variables cho pagination và search
    let promotionData = {
        rank: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 },
        general: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 },
        higher: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 }
    };
    let searchQuery = '';
    let loadingPromotions = false;

    // Hàm toggle promotion section
    function togglePromotionSection(type) {
        const content = document.getElementById(`${type === 'rank' ? 'rank' : type === 'general' ? 'general' : 'higher-rank'}-promotions-content`);
        console.log(`[DEBUG] Toggling promotion section for ${type}, content ID: ${type === 'rank' ? 'rank' : type === 'general' ? 'general' : 'higher-rank'}-promotions-content`);
        if (!content) {
            console.error(`[DEBUG] Content element not found for type: ${type}`);
            return;
        }
        const icon = content.previousElementSibling.querySelector('.promotion-toggle-icon');
        
        if (content.classList.contains('collapsed')) {
            content.classList.remove('collapsed');
            icon.classList.remove('collapsed');
        } else {
            content.classList.add('collapsed');
            icon.classList.add('collapsed');
        }
    }

    // Hàm hiển thị thêm promotions với loading state
    function showMorePromotions(type) {
        if (loadingPromotions) return;
        
        loadingPromotions = true;
        const data = promotionData[type];
        const sourceData = searchQuery ? data.filtered : data.all;
        
        // Hiển thị loading
        const loadingElement = document.getElementById(type === 'higher' ? 'higher-rank-loading' : `${type}-loading`);
        const loadTextElement = document.getElementById(type === 'higher' ? 'higher-rank-load-text' : `${type}-load-text`);
        if (loadingElement) loadingElement.style.display = 'block';
        if (loadTextElement) loadTextElement.style.display = 'none';
        
        // Simulate loading delay for better UX (có thể bỏ trong production)
        setTimeout(() => {
            data.currentPage++;
            
            const startIndex = data.currentPage * data.pageSize;
            const endIndex = Math.min(startIndex + data.pageSize, sourceData.length);
            const newPromotions = sourceData.slice(startIndex, endIndex);
            
            // Batch render để tối ưu hiệu năng
            const listId = `${type === 'rank' ? 'rank' : type === 'general' ? 'general' : 'higher-rank'}-promotions-list`;
            console.log(`[DEBUG] Getting list element with ID: ${listId}`);
            const listElement = document.getElementById(listId);
            if (!listElement) {
                console.error(`[DEBUG] List element ${listId} not found!`);
                loadingPromotions = false;
                return;
            }
            const fragment = document.createDocumentFragment();
            
            newPromotions.forEach(promotion => {
                fragment.appendChild(createPromotionButton(promotion, type));
            });
            
            listElement.appendChild(fragment);
            
            // Ẩn loading
            if (loadingElement) loadingElement.style.display = 'none';
            if (loadTextElement) loadTextElement.style.display = 'block';
            
            // Cập nhật nút "Xem thêm"
            const showMoreBtn = document.getElementById(`${type}-show-more`);
            if (endIndex >= sourceData.length) {
                if (showMoreBtn) showMoreBtn.style.display = 'none';
            } else {
                if (loadTextElement) {
                    loadTextElement.textContent = `Xem thêm (còn ${sourceData.length - endIndex} mã)`;
                }
            }
            
            loadingPromotions = false;
            console.log(`Loaded page ${data.currentPage + 1} for ${type} promotions: ${newPromotions.length} items`);
        }, 300); // 300ms loading simulation
    }

    // Hàm tạo promotion button với improved performance
    function createPromotionButton(promotion, type) {
        console.log(`[DEBUG] Creating button for promotion:`, {code: promotion.code, type: type, rank: promotion.rank});
        
        if (!promotion || !promotion.code) {
            console.error('[DEBUG] Invalid promotion data:', promotion);
            return null;
        }
        
        const promotionBtn = document.createElement('div');
        
        // Tính toán và hiển thị discount text dựa trên loại giảm giá
        let discountText = '';
        if (promotion.discount_type === 'percentage') {
            // Mã phần trăm: hiển thị % và giới hạn tối đa nếu có
            discountText = `${promotion.discount_value}%`;
            if (promotion.max_discount_amount) {
                discountText += ` (tối đa ${numberFormat(promotion.max_discount_amount)}₫)`;
            }
        } else if (promotion.discount_type === 'fixed') {
            // Mã cố định: hiển thị số tiền cố định
            discountText = `${numberFormat(promotion.discount_value)}₫`;
        } else {
            // Fallback: sử dụng display_text nếu có
            discountText = promotion.display_text || `${numberFormat(promotion.discount_amount || 0)}₫`;
        }
        
        // Sử dụng template string để tối ưu hiệu năng
        let buttonClass, buttonContent, clickHandler;
        
        if (type === 'higher') {
            // Higher rank promotions - không thể click
            const rankName = promotion.rank || 'Cao hơn';
            const rankClass = getRankCssClass(rankName);
            const rankIcon = getRankIcon(rankName);
            buttonClass = `promotion-suggestion-btn compact rank-${rankClass}-btn`;
            
            buttonContent = `
                <div style="font-weight: 600;">
                    <span class="promotion-rank-icon">${rankIcon}</span>
                    ${promotion.code || 'N/A'}
                </div>
                <div class="promotion-info">
                    Hạng ${rankName} - 
                    <span class="promotion-discount-highlight">Giảm ${discountText}</span>
                </div>
            `;
            
            promotionBtn.style.opacity = '0.6';
            promotionBtn.style.cursor = 'not-allowed';
            promotionBtn.title = `Mã này dành cho hạng ${rankName}. Hãy nâng hạng để sử dụng!`;
            
            clickHandler = () => {
                alert(`Mã này dành cho hạng ${rankName}. Hãy nâng hạng để sử dụng!`);
            };
        } else if (type === 'rank') {
            // User's rank promotions
            const rankName = promotion.rank || 'VIP';
            const rankClass = getRankCssClass(rankName);
            const rankIcon = getRankIcon(rankName);
            buttonClass = `promotion-suggestion-btn compact rank-${rankClass}-btn`;
            
            buttonContent = `
                <div style="font-weight: 600;">
                    <span class="promotion-rank-icon">${rankIcon}</span>
                    ${promotion.code || 'N/A'}
                </div>
                <div class="promotion-info">
                    Hạng ${rankName} - 
                    <span class="promotion-discount-highlight">Giảm ${discountText}</span>
                </div>
            `;
            
            promotionBtn.title = `Click để áp dụng mã ${promotion.code}`;
            clickHandler = () => selectPromotionCode(promotion.code);
        } else {
            // General promotions
            buttonClass = 'promotion-suggestion-btn compact general-promotion-btn';
            
            buttonContent = `
                <div style="font-weight: 600;">
                    <span class="promotion-rank-icon">🎁</span>
                    ${promotion.code || 'N/A'}
                </div>
                <div class="promotion-info">
                    Tất cả thành viên - 
                    <span class="promotion-discount-highlight">Giảm ${discountText}</span>
                </div>
            `;
            
            promotionBtn.title = `Click để áp dụng mã ${promotion.code}`;
            clickHandler = () => selectPromotionCode(promotion.code);
        }
        
        promotionBtn.className = buttonClass;
        promotionBtn.innerHTML = buttonContent;
        promotionBtn.onclick = clickHandler;
        
        // Debug info
        console.log(`[DEBUG] Created button with class: ${buttonClass}`);
        console.log(`[DEBUG] Button content preview:`, buttonContent.substring(0, 100) + '...');
        
        // Thêm animation data attribute để có thể track performance nếu cần
        promotionBtn.setAttribute('data-promotion-code', promotion.code);
        promotionBtn.setAttribute('data-promotion-type', type);
        
        return promotionBtn;
    }

    // Hàm load danh sách mã giảm giá có sẵn
    function loadAvailablePromotions() {
        fetch('{{ route('client.getAvailablePromotions') }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('[DEBUG] loadAvailablePromotions response:', data);
            console.log('[DEBUG] Raw data structure:', {
                success: data.success,
                user_rank: data.user_rank,
                user_rank_id: data.user_rank_id,
                data: data.data,
                user_rank_data: data.data?.user_rank,
                general_data: data.data?.general,
                higher_ranks_data: data.data?.higher_ranks
            });
            
            if (data.success && data.data) {
                // Đếm tổng số mã giảm giá từ dữ liệu đã phân loại
                const userRankCount = (data.data.user_rank || []).length;
                const generalCount = (data.data.general || []).length;
                const higherRanksCount = (data.data.higher_ranks || []).length;
                const totalCount = userRankCount + generalCount + higherRanksCount;
                
                console.log('[DEBUG] Promotion counts:', {
                    user_rank: userRankCount,
                    general: generalCount,
                    higher_ranks: higherRanksCount,
                    total: totalCount
                });
                
                // Cập nhật placeholder cho input
                const promotionCodeInput = document.getElementById('promotion-code-input');
                if (promotionCodeInput && !promotionCodeInput.readOnly && totalCount > 0) {
                    let suggestion = `Có ${totalCount} mã giảm giá khả dụng`;
                    
                    if (data.user_rank && data.user_rank !== 'Khách thường' && userRankCount > 0) {
                        suggestion += ` (${userRankCount} mã VIP dành cho hạng ${data.user_rank})`;
                    } else if (generalCount > 0) {
                        suggestion += ` (${generalCount} mã chung)`;
                    }
                    
                    promotionCodeInput.placeholder = suggestion;
                    
                    console.log('[DEBUG] Available promotions loaded:', {
                        user_rank: data.user_rank,
                        user_rank_promotions: userRankCount,
                        general_promotions: generalCount,
                        higher_rank_promotions: higherRanksCount,
                        total_promotions: totalCount
                    });
                }
            } else {
                console.log('[DEBUG] No promotions available');
            }
        })
        .catch(error => {
            console.error('Error loading available promotions:', error);
        });
    }

    // Hàm để reload promotion data và cập nhật giao diện
    function refreshPromotionData() {
        console.log('[DEBUG] Refreshing promotion data...');
        const refreshBtn = document.getElementById('refresh-promotions-btn');
        if (refreshBtn) {
            refreshBtn.disabled = true;
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
        }
        
        // Lưu lại số lượng mã giảm giá trước khi refresh
        const oldPromotionCounts = {
            rank: (promotionData.rank && promotionData.rank.all) ? promotionData.rank.all.length : 0,
            general: (promotionData.general && promotionData.general.all) ? promotionData.general.all.length : 0,
            higher: (promotionData.higher && promotionData.higher.all) ? promotionData.higher.all.length : 0
        };
        
        const oldTotalCount = oldPromotionCounts.rank + oldPromotionCounts.general + oldPromotionCounts.higher;
        console.log('[DEBUG] Old promotion counts:', oldPromotionCounts, 'Total:', oldTotalCount);
        
        // Lưu các mã để phát hiện mã mới
        const oldPromotionCodes = {
            rank: promotionData.rank?.all?.map(p => p.code) || [],
            general: promotionData.general?.all?.map(p => p.code) || [],
            higher: promotionData.higher?.all?.map(p => p.code) || []
        };
        
        // Reload data từ backend
        showAvailablePromotionsSuggestion().then(() => {
            console.log('[DEBUG] Promotion data refreshed successfully');
            if (refreshBtn) {
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Làm mới';
            }
            
            // Tính số lượng mã mới
            const newPromotionCounts = {
                rank: (promotionData.rank && promotionData.rank.all) ? promotionData.rank.all.length : 0,
                general: (promotionData.general && promotionData.general.all) ? promotionData.general.all.length : 0,
                higher: (promotionData.higher && promotionData.higher.all) ? promotionData.higher.all.length : 0
            };
            
            const newTotalCount = newPromotionCounts.rank + newPromotionCounts.general + newPromotionCounts.higher;
            console.log('[DEBUG] New promotion counts:', newPromotionCounts, 'Total:', newTotalCount);
            
            // Tìm các mã mới
            const newCodes = {
                rank: promotionData.rank?.all?.filter(p => !oldPromotionCodes.rank.includes(p.code)).map(p => p.code) || [],
                general: promotionData.general?.all?.filter(p => !oldPromotionCodes.general.includes(p.code)).map(p => p.code) || [],
                higher: promotionData.higher?.all?.filter(p => !oldPromotionCodes.higher.includes(p.code)).map(p => p.code) || []
            };
            
            const totalNewCodes = newCodes.rank.length + newCodes.general.length + newCodes.higher.length;
            console.log('[DEBUG] New codes detected:', newCodes, 'Total new:', totalNewCodes);
            
            // Kiểm tra có thay đổi nào không
            const hasChanges = newTotalCount !== oldTotalCount || totalNewCodes > 0;
            
            // Hiển thị thông báo thành công với thông tin thay đổi
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${hasChanges ? '#10b981' : '#3b82f6'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                font-size: 14px;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            
            if (hasChanges) {
                // Có sự thay đổi
                if (totalNewCodes > 0) {
                    // Hiển thị danh sách mã mới
                    let newCodesMessage = `<i class="fas fa-check-circle"></i> Đã tìm thấy ${totalNewCodes} mã giảm giá mới!`;
                    
                    // Hiển thị chi tiết nếu có thay đổi trong từng loại
                    if (newCodes.rank.length > 0) {
                        newCodesMessage += `<br><small>• ${newCodes.rank.length} mã VIP: ${newCodes.rank.join(', ')}</small>`;
                    }
                    if (newCodes.general.length > 0) {
                        newCodesMessage += `<br><small>• ${newCodes.general.length} mã chung: ${newCodes.general.join(', ')}</small>`;
                    }
                    if (newCodes.higher.length > 0) {
                        newCodesMessage += `<br><small>• ${newCodes.higher.length} mã hạng cao: ${newCodes.higher.join(', ')}</small>`;
                    }
                    
                    toast.innerHTML = newCodesMessage;
                    toast.style.padding = '12px 16px';
                } else if (newTotalCount > oldTotalCount) {
                    // Số lượng mã tăng lên
                    toast.innerHTML = `<i class="fas fa-check-circle"></i> Đã cập nhật! Có thêm ${newTotalCount - oldTotalCount} mã giảm giá.`;
                } else {
                    // Số lượng mã giảm đi
                    toast.innerHTML = `<i class="fas fa-check-circle"></i> Đã cập nhật! Có ${oldTotalCount - newTotalCount} mã giảm giá đã hết hạn.`;
                }
            } else {
                // Không có sự thay đổi
                toast.innerHTML = '<i class="fas fa-info-circle"></i> Danh sách mã giảm giá đã được cập nhật';
            }
            
            document.body.appendChild(toast);
            
            // Hiển thị toast
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Ẩn toast sau 3 giây
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
            
            // Highlight các mã mới nếu có
            if (totalNewCodes > 0) {
                setTimeout(() => {
                    // Highlight rank promotions
                    if (newCodes.rank.length > 0) {
                        const rankList = document.getElementById('rank-promotions-list');
                        if (rankList) {
                            newCodes.rank.forEach(code => {
                                const codeBtn = rankList.querySelector(`[data-promotion-code="${code}"]`);
                                if (codeBtn) {
                                    codeBtn.classList.add('new-promotion');
                                    codeBtn.setAttribute('data-is-new', 'true');
                                }
                            });
                        }
                    }
                    
                    // Highlight general promotions
                    if (newCodes.general.length > 0) {
                        const generalList = document.getElementById('general-promotions-list');
                        if (generalList) {
                            newCodes.general.forEach(code => {
                                const codeBtn = generalList.querySelector(`[data-promotion-code="${code}"]`);
                                if (codeBtn) {
                                    codeBtn.classList.add('new-promotion');
                                    codeBtn.setAttribute('data-is-new', 'true');
                                }
                            });
                        }
                    }
                    
                    // Highlight higher rank promotions
                    if (newCodes.higher.length > 0) {
                        const higherList = document.getElementById('higher-promotions-list');
                        if (higherList) {
                            newCodes.higher.forEach(code => {
                                const codeBtn = higherList.querySelector(`[data-promotion-code="${code}"]`);
                                if (codeBtn) {
                                    codeBtn.classList.add('new-promotion');
                                    codeBtn.setAttribute('data-is-new', 'true');
                                }
                            });
                        }
                    }
                    
                    // Add style for new promotions if not already added
                    if (!document.getElementById('new-promotion-styles')) {
                        const style = document.createElement('style');
                        style.id = 'new-promotion-styles';
                        style.innerHTML = `
                            .new-promotion {
                                animation: pulse-highlight 2s infinite;
                                box-shadow: 0 0 8px rgba(34, 197, 94, 0.6);
                            }
                            @keyframes pulse-highlight {
                                0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
                                70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
                                100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
                            }
                        `;
                        document.head.appendChild(style);
                    }
                }, 500);
            }
        }).catch((error) => {
            console.error('[DEBUG] Error refreshing promotion data:', error);
            if (refreshBtn) {
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Làm mới';
            }
            
            // Hiển thị thông báo lỗi
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #ef4444;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                font-size: 14px;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            toast.innerHTML = '<i class="fas fa-exclamation-circle"></i> Không thể cập nhật dữ liệu. Vui lòng thử lại sau.';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        });
    }

    // Khởi tạo khi trang tải
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded fired, initializing buttons...');
        initApplyPointsButton();
    });
    
    window.onload = function() {
        console.log('Page loaded, initializing...');
        
        showStep(currentStep);
        document.getElementById("screen-next-btn").disabled = true;
        updateShowtimes(selectedDate);
        
        // Khởi tạo các nút promotion
        initPromotionButtons();
        
        // Khởi tạo nút đổi điểm (đảm bảo DOM đã load)
        initApplyPointsButton();
    };
    
    // Hàm khởi tạo nút đổi điểm riêng
    function initApplyPointsButton() {
        console.log('Initializing apply points button...');
        const applyPointsBtn = document.getElementById('apply-points-btn');
        console.log('Apply points button element:', applyPointsBtn);
        
        if (!applyPointsBtn) {
            console.error('Apply points button not found!');
            return;
        }
        
        applyPointsBtn.onclick = function() {
            console.log('Apply points button clicked!');
            applyPointsBtn.disabled = true;
            
            const pointsInputEl = document.getElementById('points-input');
            console.log('Points input element:', pointsInputEl);
            
            if (!pointsInputEl) {
                console.error('Points input not found!');
                applyPointsBtn.disabled = false;
                return;
            }
            
            const points = parseInt(pointsInputEl.value) || 0;
            const availablePointsEl = document.getElementById('available-points');
            let availablePoints = parseInt(availablePointsEl.textContent) || 0;
            const subtotalEl = document.getElementById('subtotalDisplay');
            const subtotal = parseInt(subtotalEl.textContent.replace(/[^\d]/g, '')) || 0;
            const currentDiscount = promotionDiscount + pointsDiscount; // Tổng giảm giá hiện tại
            
            console.log('Applying points:', {
                points: points,
                subtotal: subtotal,
                promotionDiscount: promotionDiscount,
                pointsDiscount: pointsDiscount,
                currentTotalDiscount: currentDiscount,
                availablePoints: availablePoints,
                pointsInputValue: pointsInputEl.value,
                subtotalText: subtotalEl.textContent
            });
            
            if (points <= 0) {
                alert(`Vui lòng nhập số điểm hợp lệ (lớn hơn 0).`);
                applyPointsBtn.disabled = false;
                return;
            }
            
            if (points > availablePoints || isNaN(availablePoints)) {
                alert(`Bạn chỉ có ${availablePoints} điểm khả dụng.`);
                applyPointsBtn.disabled = false;
                return;
            }
            
            // Kiểm tra giới hạn 30% phía frontend
            const pointDiscount = points * 1000;
            const maxTotalDiscount = subtotal * 0.3;
            const totalDiscount = promotionDiscount + pointDiscount;
            
            if (totalDiscount > maxTotalDiscount) {
                const maxPointsAllowed = Math.floor((maxTotalDiscount - promotionDiscount) / 1000);
                alert(`Tổng giảm giá không được vượt quá 30% đơn hàng. Bạn chỉ có thể dùng tối đa ${maxPointsAllowed} điểm.`);
                applyPointsBtn.disabled = false;
                return;
            }
            
            fetch('{{ route('client.applyPoints') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        points: points,
                        order_amount: subtotal,
                        current_discount: promotionDiscount // Chỉ truyền discount từ mã khuyến mãi
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data received:', data);
                    if (data.success) {
                        // Cập nhật điểm đã dùng
                        pointsDiscount = data.discount;
                        discount = promotionDiscount + pointsDiscount; // Tổng discount
                        
                        console.log('Updated discount after adding points:', {
                            promotionDiscount: promotionDiscount,
                            pointsDiscount: pointsDiscount,
                            totalDiscount: discount,
                            pointsUsed: points
                        });
                        
                        // Cập nhật giao diện điểm
                        availablePointsEl.textContent = availablePoints - points;
                        document.getElementById('points-used-line').textContent = points;
                        
                        // Cập nhật tất cả các element hiển thị discount
                        const discountElements = [
                            'discountDisplay',
                            'voucher-discount-line',
                            'discount-amount'
                        ];
                        
                        discountElements.forEach(id => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.textContent = numberFormat(discount) + ' ₫';
                                console.log(`Updated ${id}:`, element.textContent);
                            }
                        });
                        
                        // Cập nhật order summary để tính lại tổng tiền
                        updateOrderSummary();
                        
                        // Hiển thị thông báo thành công với chi tiết
                        const pointValue = points * 1000;
                        alert(`Đổi điểm thành công!\n${points} điểm = ${numberFormat(pointValue)}₫\nTổng giảm giá: ${numberFormat(discount)}₫`);
                        
                        // Reset input
                        pointsInputEl.value = '0';
                    } else {
                        console.error('Server returned error:', data.error);
                        alert(data.error || 'Có lỗi khi đổi điểm.');
                    }
                })
                .catch(error => {
                    console.error('Error applying points:', error);
                    console.error('Error stack:', error.stack);
                    alert('Đã có lỗi xảy ra khi đổi điểm: ' + error.message);
                })
                .finally(() => {
                    console.log('Request completed, re-enabling button');
                    applyPointsBtn.disabled = false;
                });
        };
        console.log('Apply points button event handler attached');
    }
    
    window.onload = function() {
        console.log('Page loaded, initializing...');
        
        showStep(currentStep);
        document.getElementById("screen-next-btn").disabled = true;
        updateShowtimes(selectedDate);
        
        // Khởi tạo các nút promotion
        initPromotionButtons();
        
        // Khởi tạo nút đổi điểm (đảm bảo DOM đã load)
        initApplyPointsButton();
        
        // Load danh sách mã giảm giá có sẵn
        loadAvailablePromotions();
        
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', handleNextStep);
        });
        document.querySelectorAll('.previous-step').forEach(button => {
            button.addEventListener('click', handlePreviousStep);
        });

        document.querySelectorAll('.variant-select').forEach(select => {
            const productId = select.getAttribute('data-product-id');
            const selectedOption = select.options[select.selectedIndex];
            variantData[productId] = {
                variantName: selectedOption.text.split(' (')[0],
                price: parseFloat(selectedOption.getAttribute('data-price')) || 0
            };
            select.addEventListener('change', () => updateVariant(productId));
        });

        updateOrderSummary();
        
        // Cleanup intervals khi user rời khỏi trang
        window.addEventListener('beforeunload', function() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
            console.log('Cleaned up intervals before unloading page');
        });
        
        // Thêm keyboard shortcuts để refresh promotion data
        document.addEventListener('keydown', function(e) {
            // Chỉ hoạt động khi đang ở step 4 (payment step) và focus vào promotion section
            if (currentStep === 4) {
                const promotionSection = document.getElementById('available-promotions-section');
                const promotionInput = document.getElementById('promotion-code-input');
                const searchInput = document.getElementById('global-promotion-search');
                
                // Kiểm tra nếu user đang focus trong promotion section
                const isInPromotionArea = promotionSection && (
                    promotionSection.contains(document.activeElement) ||
                    document.activeElement === promotionInput ||
                    document.activeElement === searchInput
                );
                
                // F5 hoặc Ctrl+R để refresh promotion data
                if ((e.key === 'F5' || (e.ctrlKey && e.key === 'r')) && isInPromotionArea) {
                    e.preventDefault();
                    console.log('Keyboard shortcut triggered: refreshing promotion data');
                    refreshPromotionData();
                }
                
                // Ctrl+Shift+R để force refresh và clear cache
                if (e.ctrlKey && e.shiftKey && e.key === 'R' && isInPromotionArea) {
                    e.preventDefault();
                    console.log('Force refresh triggered: clearing and reloading promotion data');
                    
                    // Clear promotion data
                    promotionData = {
                        rank: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 },
                        general: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 },
                        higher: { all: [], displayed: [], filtered: [], pageSize: 6, currentPage: 0 }
                    };
                    
                    // Clear UI
                    const availablePromotionsSection = document.getElementById('available-promotions-section');
                    if (availablePromotionsSection) {
                        availablePromotionsSection.style.display = 'none';
                    }
                    
                    // Refresh with delay to show loading effect
                    setTimeout(() => {
                        refreshPromotionData();
                    }, 300);
                }
            }
        });
        
        console.log('Page initialization completed');
    };
</script>

<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'>
</script>
<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
