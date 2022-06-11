<?php

namespace App\Jobs;

use App\Mail\ReviewLinkMail;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\Mail;

class SendReviewLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reviewSlots = $this->getReviewSlots();

        foreach ($reviewSlots as $reviewSlot) {
            Log::info('Send review mail to ' . $reviewSlot->purchaserEmail);

            try {
                Mail::to($reviewSlot->purchaserEmail)->send(new ReviewLinkMail($reviewSlot));

                $this->markAsSent($reviewSlot->id);
            } catch (Exception $e) {
                Log::error('Send review mail failed, email -  ' . $reviewSlot->purchaserEmail);
                Log::error($e);
            }
        }
    }

    private function getReviewSlots(): Collection
    {
        $reviewSlots = DB::table('reviews')
            ->where([
                ['is_key_sent', '=', 0],
                ['reviews.created_at', '<', date('Y-m-d H:i:s', strtotime('-1 days'))]
            ])
            ->join('videos', 'videos.id', '=', 'reviews.video_id')
            ->join('orders', 'orders.id', '=', 'videos.order_id')
            ->join('users', 'users.id', '=', 'videos.user_id')
            ->select('reviews.id', 'purchaser_email as purchaserEmail', 'purchaser_name as purchaserName', 'users.nick', 'reviews.access_key as key')
            ->get();

        return $reviewSlots;
    }

    private function markAsSent(int $id)
    {
        Review::where('id', $id)->update(['is_key_sent' => true]);
    }

    public function failed(Throwable $exception)
    {
        Log::error($exception);
    }
}
