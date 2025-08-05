<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SensitiveWord;

class SensitiveWordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sensitiveWords = [
            // Từ ngữ tục tĩu
            ['word' => 'đồ khốn', 'category' => 'offensive'],
            ['word' => 'đồ chó', 'category' => 'offensive'],
            ['word' => 'đồ ngu', 'category' => 'offensive'],
            ['word' => 'thằng ngu', 'category' => 'offensive'],
            ['word' => 'con chó', 'category' => 'offensive'],
            ['word' => 'đéo', 'category' => 'offensive'],
            ['word' => 'địt', 'category' => 'offensive'],
            ['word' => 'cặc', 'category' => 'offensive'],
            ['word' => 'lồn', 'category' => 'offensive'],
            ['word' => 'buồi', 'category' => 'offensive'],
            ['word' => 'đụ', 'category' => 'offensive'],
            ['word' => 'vcl', 'category' => 'offensive'],
            ['word' => 'vãi', 'category' => 'offensive'],
            ['word' => 'đm', 'category' => 'offensive'],
            ['word' => 'dm', 'category' => 'offensive'],
            ['word' => 'clgt', 'category' => 'offensive'],
            ['word' => 'đcm', 'category' => 'offensive'],
            ['word' => 'dcm', 'category' => 'offensive'],
            ['word' => 'vcc', 'category' => 'offensive'],
            ['word' => 'cc', 'category' => 'offensive'],
            ['word' => 'đkm', 'category' => 'offensive'],
            ['word' => 'dkm', 'category' => 'offensive'],
            
            // Từ ngữ kỳ thị
            ['word' => 'ngu si', 'category' => 'discriminatory'],
            ['word' => 'đần độn', 'category' => 'discriminatory'],
            ['word' => 'khờ dại', 'category' => 'discriminatory'],
            ['word' => 'não cá vàng', 'category' => 'discriminatory'],
            ['word' => 'não tôm', 'category' => 'discriminatory'],
            ['word' => 'retard', 'category' => 'discriminatory'],
            ['word' => 'stupid', 'category' => 'discriminatory'],
            ['word' => 'idiot', 'category' => 'discriminatory'],
            ['word' => 'moron', 'category' => 'discriminatory'],
            ['word' => 'dumb', 'category' => 'discriminatory'],
            
            // Từ ngữ phân biệt chủng tộc/tôn giáo
            ['word' => 'chó má', 'category' => 'racist'],
            ['word' => 'thằng tàu', 'category' => 'racist'],
            ['word' => 'thằng tây', 'category' => 'racist'],
            ['word' => 'con mỹ', 'category' => 'racist'],
            ['word' => 'thằng nhật', 'category' => 'racist'],
            
            // Từ ngữ đe dọa
            ['word' => 'giết', 'category' => 'threatening'],
            ['word' => 'chết đi', 'category' => 'threatening'],
            ['word' => 'đi chết', 'category' => 'threatening'],
            ['word' => 'tự tử', 'category' => 'threatening'],
            ['word' => 'giết chết', 'category' => 'threatening'],
            ['word' => 'kill', 'category' => 'threatening'],
            ['word' => 'die', 'category' => 'threatening'],
            ['word' => 'death', 'category' => 'threatening'],
            ['word' => 'murder', 'category' => 'threatening'],
            
            // Spam/quảng cáo
            ['word' => 'mua bán', 'category' => 'spam'],
            ['word' => 'quảng cáo', 'category' => 'spam'],
            ['word' => 'khuyến mãi', 'category' => 'spam'],
            ['word' => 'giảm giá', 'category' => 'spam'],
            ['word' => 'liên hệ', 'category' => 'spam'],
            ['word' => 'zalo', 'category' => 'spam'],
            ['word' => 'facebook', 'category' => 'spam'],
            ['word' => 'instagram', 'category' => 'spam'],
            ['word' => 'tiktok', 'category' => 'spam'],
            
            // Nội dung khiêu dâm
            ['word' => 'sex', 'category' => 'adult'],
            ['word' => 'porn', 'category' => 'adult'],
            ['word' => 'xxx', 'category' => 'adult'],
            ['word' => 'nude', 'category' => 'adult'],
            ['word' => 'naked', 'category' => 'adult'],
            ['word' => 'sexy', 'category' => 'adult'],
            ['word' => 'địt nhau', 'category' => 'adult'],
            ['word' => 'quan hệ', 'category' => 'adult'],
            ['word' => 'làm tình', 'category' => 'adult'],
            ['word' => 'chịch', 'category' => 'adult'],
        ];

        foreach ($sensitiveWords as $wordData) {
            SensitiveWord::updateOrCreate(
                ['word' => $wordData['word']],
                [
                    'category' => $wordData['category'],
                    'is_active' => true,
                    'created_by' => null
                ]
            );
        }
    }
}
