<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Danh sách từ khóa nhạy cảm
    |--------------------------------------------------------------------------
    |
    | Các từ khóa này sẽ được kiểm tra trong bình luận.
    | Nếu phát hiện, bình luận sẽ chuyển sang trạng thái "pending" để duyệt.
    |
    */
    
    'sensitive_words' => [
        // Từ ngữ tục tĩu
        'fuck', 'shit', 'damn', 'bitch', 'asshole', 'bastard',
        'đồ khốn', 'đồ chó', 'đồ ngu', 'thằng ngu', 'con chó', 'đéo',
        'địt', 'cặc', 'lồn', 'buồi', 'đụ', 'vcl', 'vãi', 'đm', 'dm',
        'clgt', 'đcm', 'dcm', 'vcc', 'cc', 'đkm', 'dkm',
        
        // Từ ngữ kỳ thị
        'ngu si', 'đần độn', 'khờ dại', 'não cá vàng', 'não tôm',
        'retard', 'stupid', 'idiot', 'moron', 'dumb',
        
        // Từ ngữ phân biệt chủng tộc/tôn giáo
        'chó má', 'thằng tàu', 'thằng tây', 'con mỹ', 'thằng nhật',
        
        // Từ ngữ đe dọa
        'giết', 'chết đi', 'đi chết', 'tự tử', 'giết chết',
        'kill', 'die', 'death', 'murder',
        
        // Spam/quảng cáo
        'mua bán', 'quảng cáo', 'khuyến mãi', 'giảm giá',
        'liên hệ', 'zalo', 'facebook', 'instagram', 'tiktok',
        'www.', 'http', '.com', '.vn', '.net',
        
        // Nội dung khiêu dâm
        'sex', 'porn', 'xxx', 'nude', 'naked', 'sexy',
        'địt nhau', 'quan hệ', 'làm tình', 'chịch',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Các pattern regex để kiểm tra
    |--------------------------------------------------------------------------
    */
    
    'regex_patterns' => [
        // Email pattern
        '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/',
        
        // Phone number pattern (Vietnam)
        '/(\+84|84|0)(3|5|7|8|9)([0-9]{8})/',
        
        // URL pattern
        '/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/',
        
        // Repeated characters (spam detection)
        '/(.)\1{4,}/', // 5 or more repeated characters
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Cài đặt kiểm duyệt
    |--------------------------------------------------------------------------
    */
    
    'auto_approve' => [
        'min_length' => 10, // Bình luận tối thiểu 10 ký tự mới tự động duyệt
        'max_length' => 500, // Bình luận quá 500 ký tự cần duyệt
        'check_user_history' => false, // Tắt kiểm tra lịch sử người dùng - chỉ focus vào nội dung
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Điểm số tin cậy người dùng
    |--------------------------------------------------------------------------
    */
    
    'user_trust_score' => [
        'new_user_threshold' => 7, // Người dùng mới (dưới 7 ngày) cần duyệt
        'min_approved_reviews' => 3, // Tối thiểu 3 review đã duyệt mới tự động duyệt
    ],
];
