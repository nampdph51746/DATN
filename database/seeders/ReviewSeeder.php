<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Movie;
use App\Models\Booking;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $movies = Movie::take(10)->get();

        if ($users->count() == 0 || $movies->count() == 0) {
            $this->command->warn('Cần có users và movies trong database trước khi tạo reviews');
            return;
        }

        $reviews = [
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 5,
                'comment' => 'Phim rất hay, diễn xuất tuyệt vời, cốt truyện hấp dẫn. Đáng xem!',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 4,
                'comment' => 'Phim khá ổn, có một số cảnh hay nhưng hơi dài dòng. Nhìn chung là tốt.',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 3,
                'comment' => 'Phim bình thường, không có gì đặc sắc lắm.',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 5,
                'comment' => 'Tuyệt vời! Một trong những bộ phim hay nhất tôi từng xem. Cảm ơn rạp!',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 2,
                'comment' => 'Phim không hay, cốt truyện lủng củng. Hơi thất vọng.',
                'status' => 'pending'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 4,
                'comment' => 'Phim hay, diễn viên đẹp, hiệu ứng ổn. Recommend!',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 1,
                'comment' => 'Phim tệ, lãng phí tiền.',
                'status' => 'rejected'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 5,
                'comment' => 'Phim xuất sắc từ diễn xuất đến kỹ xảo. Sẽ xem lại!',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 4,
                'comment' => 'Không tệ, có thể xem được. Khuyến khích các bạn trẻ xem.',
                'status' => 'pending'
            ],
            [
                'user_id' => $users->random()->id,
                'movie_id' => $movies->random()->id,
                'rating_star' => 3,
                'comment' => null, // Review không có comment
                'status' => 'approved'
            ]
        ];

        foreach ($reviews as $reviewData) {
            // Kiểm tra user đã review phim này chưa
            $exists = Review::where('user_id', $reviewData['user_id'])
                           ->where('movie_id', $reviewData['movie_id'])
                           ->exists();
            
            if (!$exists) {
                Review::create($reviewData);
            }
        }

        $this->command->info('Đã tạo ' . count($reviews) . ' reviews mẫu');
    }
}
