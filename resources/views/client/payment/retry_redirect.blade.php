<!DOCTYPE html>
<html>
<head>
    <title>Đang chuyển hướng...</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .loading {
            text-align: center;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="loading">
        <div class="spinner"></div>
        <h3>Đang chuyển hướng đến trang thanh toán...</h3>
        <p>Vui lòng đợi trong giây lát...</p>
    </div>

    <form id="vnpayForm" action="{{ $vnpay_url }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="final_amount" value="{{ $final_amount }}">
        <input type="hidden" name="retry_booking_id" value="{{ $retry_booking_id }}">
        <input type="hidden" name="redirect" value="1">
    </form>

    <script>
        // Auto submit form after 1 second
        setTimeout(function() {
            document.getElementById('vnpayForm').submit();
        }, 1000);
    </script>
</body>
</html>
