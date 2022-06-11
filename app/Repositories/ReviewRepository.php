<?php

namespace App\Repositories;

use App\Interfaces\ReviewInterface;
use App\Models\Review;
use DB;

class ReviewRepository implements ReviewInterface
{
    public function checkKey(string $key)
    {
        $review = Review::where('access_key', $key)->first();

        if (isset($review)) {
            return (object) [
                'code' => 200,
                'data' => (object) [
                    'isUsed' => $review->rate ? true : false
                ]
            ];
        }

        return (object) ['code' => '500', 'message' => 'Podany kod nie istnieje!'];
    }

    public function saveReview(object $review)
    {
        Review::where('access_key', $review->key)
            ->update([
                'rate' => $review->rate,
                'client_name' => $review->name,
                'text' => $review->text
            ]);
    }

    public function getReviews(string $userNick)
    {
        $reviews = DB::table('reviews')
            ->where('rate', '!=', null)
            ->join('videos', 'videos.id', '=', 'reviews.video_id')
            ->join('users', function ($join) use ($userNick) {
                $join->on('users.id', '=', 'videos.user_id')
                ->where('users.nick', $userNick);
            })
            ->select('rate', 'client_name as name', 'text', 'reviews.updated_at as createdAt')
            ->get();

        return $reviews;
    }
}