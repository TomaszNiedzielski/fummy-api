<?php

namespace App\Interfaces;

interface ReviewInterface
{
    /**
     * Checks if access key is correct
     * 
     * @method  POST  api/reviews/check-key
     */
    public function checkKey(string $key);

    /**
     * Creates review using access key
     * 
     * @method  POST  api/reviews
     */
    public function saveReview(object $review);

    /**
     * Get reviews for user
     * 
     * @method  GET  api/reviews
     */
    public function getReviews(string $userNick);
}