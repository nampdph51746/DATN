<?php

namespace App\Services;

use App\Models\User;
use App\Models\Review;
use App\Models\SensitiveWord;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ContentFilterService
{
    private $sensitiveWords;
    private $regexPatterns;
    private $autoApproveConfig;
    private $userTrustConfig;

    public function __construct()
    {
        // Kiểm tra xem bảng có tồn tại không trước khi truy vấn
        try {
            if (Schema::hasTable('sensitive_words')) {
                $this->sensitiveWords = SensitiveWord::active()->pluck('word')->toArray();
            } else {
                $this->sensitiveWords = [];
                Log::info('Sensitive words table does not exist yet, using empty array');
            }
        } catch (\Exception $e) {
            // Fallback nếu có lỗi khác
            $this->sensitiveWords = [];
            Log::warning('Error loading sensitive words: ' . $e->getMessage());
        }
        
        $this->regexPatterns = Config::get('content_filter.regex_patterns', []);
        $this->autoApproveConfig = Config::get('content_filter.auto_approve', []);
        $this->userTrustConfig = Config::get('content_filter.user_trust_score', []);
    }

    // Lazy loading cho sensitive words
    private function getSensitiveWords()
    {
        if (empty($this->sensitiveWords) && Schema::hasTable('sensitive_words')) {
            try {
                $this->sensitiveWords = SensitiveWord::active()->pluck('word')->toArray();
            } catch (\Exception $e) {
                Log::warning('Error loading sensitive words: ' . $e->getMessage());
                $this->sensitiveWords = [];
            }
        }
        
        return $this->sensitiveWords;
    }

    /**
     * Kiểm tra nội dung và quyết định trạng thái review
     */
    public function checkContent(string $content, int $userId): array
    {
        $reasons = [];
        $shouldPending = false;

        // 1. Kiểm tra từ khóa nhạy cảm
        $sensitiveWordFound = $this->checkSensitiveWords($content);
        if ($sensitiveWordFound) {
            $reasons[] = 'Chứa từ khóa nhạy cảm: ' . $sensitiveWordFound;
            $shouldPending = true;
        }

        // 2. Kiểm tra pattern regex
        $regexViolation = $this->checkRegexPatterns($content);
        if ($regexViolation) {
            $reasons[] = 'Chứa nội dung không phù hợp: ' . $regexViolation;
            $shouldPending = true;
        }

        // 3. Kiểm tra độ dài
        $lengthIssue = $this->checkContentLength($content);
        if ($lengthIssue) {
            $reasons[] = $lengthIssue;
            $shouldPending = true;
        }

        // 5. Kiểm tra spam (ký tự lặp lại)
        $spamCheck = $this->checkSpam($content);
        if ($spamCheck) {
            $reasons[] = $spamCheck;
            $shouldPending = true;
        }

        $status = $shouldPending ? 'pending' : 'approved';

        return [
            'status' => $status,
            'reasons' => $reasons,
            'auto_flagged' => $shouldPending
        ];
    }

    /**
     * Kiểm tra từ khóa nhạy cảm
     */
    private function checkSensitiveWords(string $content): ?string
    {
        $content = mb_strtolower($content, 'UTF-8');
        $sensitiveWords = $this->getSensitiveWords(); // Sử dụng getSensitiveWords()
        
        foreach ($sensitiveWords as $word) {
            $word = mb_strtolower($word, 'UTF-8');
            if (mb_strpos($content, $word) !== false) {
                Log::info('Sensitive word detected', [
                    'word' => $word,
                    'content' => substr($content, 0, 100)
                ]);
                return $word;
            }
        }

        return null;
    }

    /**
     * Kiểm tra các pattern regex
     */
    private function checkRegexPatterns(string $content): ?string
    {
        foreach ($this->regexPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                if ($pattern === '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/') {
                    return 'email';
                }
                if ($pattern === '/(\+84|84|0)(3|5|7|8|9)([0-9]{8})/') {
                    return 'số điện thoại';
                }
                if ($pattern === '/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/') {
                    return 'đường link';
                }
                if ($pattern === '/(.)\1{4,}/') {
                    return 'ký tự lặp lại (spam)';
                }
            }
        }

        return null;
    }

    /**
     * Kiểm tra độ dài nội dung
     */
    private function checkContentLength(string $content): ?string
    {
        $length = mb_strlen($content, 'UTF-8');
        $minLength = $this->autoApproveConfig['min_length'] ?? 10;
        $maxLength = $this->autoApproveConfig['max_length'] ?? 500;

        if ($length < $minLength) {
            return "Nội dung quá ngắn (< {$minLength} ký tự)";
        }

        if ($length > $maxLength) {
            return "Nội dung quá dài (> {$maxLength} ký tự)";
        }

        return null;
    }

    /**
     * Kiểm tra spam
     */
    private function checkSpam(string $content): ?string
    {
        // Kiểm tra ký tự lặp lại nhiều
        if (preg_match('/(.)\1{4,}/', $content)) {
            return 'Có ký tự lặp lại nhiều lần';
        }

        // Kiểm tra từ lặp lại
        $words = explode(' ', $content);
        $wordCount = array_count_values($words);
        foreach ($wordCount as $word => $count) {
            if ($count > 3 && mb_strlen($word, 'UTF-8') > 2) {
                return "Từ '{$word}' lặp lại quá nhiều";
            }
        }

        // Kiểm tra tỷ lệ ký tự đặc biệt
        $specialChars = preg_match_all('/[!@#$%^&*(),.?":{}|<>]/', $content);
        $totalChars = mb_strlen($content, 'UTF-8');
        if ($totalChars > 0 && ($specialChars / $totalChars) > 0.3) {
            return 'Quá nhiều ký tự đặc biệt';
        }

        return null;
    }

    /**
     * Làm sạch nội dung (tùy chọn)
     */
    public function cleanContent(string $content): string
    {
        // Loại bỏ ký tự đặc biệt không cần thiết
        $content = preg_replace('/[^\p{L}\p{N}\s.,!?-]/u', '', $content);
        
        // Loại bỏ khoảng trắng thứa
        $content = preg_replace('/\s+/', ' ', $content);
        
        return trim($content);
    }

    /**
     * Thêm từ khóa nhạy cảm mới
     */
    public function addSensitiveWord(string $word, string $category = 'general'): void
    {
        if (!Schema::hasTable('sensitive_words')) {
            Log::warning('Cannot add sensitive word: table does not exist');
            return;
        }

        $existingWord = SensitiveWord::where('word', $word)->first();
        
        if (!$existingWord) {
            SensitiveWord::create([
                'word' => $word,
                'category' => $category,
                'is_active' => true,
                'created_by' => Auth::id()
            ]);
            
            // Refresh cache
            $this->sensitiveWords = SensitiveWord::active()->pluck('word')->toArray();
            
            Log::info('New sensitive word added: ' . $word);
        }
    }

    /**
     * Xóa từ khóa nhạy cảm
     */
    public function removeSensitiveWord(string $word): void
    {
        if (!Schema::hasTable('sensitive_words')) {
            Log::warning('Cannot remove sensitive word: table does not exist');
            return;
        }

        $sensitiveWord = SensitiveWord::where('word', $word)->first();
        
        if ($sensitiveWord) {
            $sensitiveWord->delete();
            
            // Refresh cache
            $this->sensitiveWords = SensitiveWord::active()->pluck('word')->toArray();
            
            Log::info('Sensitive word removed: ' . $word);
        }
    }
}