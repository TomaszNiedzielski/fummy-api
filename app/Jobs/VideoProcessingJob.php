<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\VideoMail;
use App\Models\{Video, Income, Order, Review};
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;

class VideoProcessingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private object $video;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(object $video, $user)
    {
        $this->video = $video;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->resizeVideo();
        $this->createThumbnail();
        $this->updateVideoAsComplete();
        $this->sendEmailToPurchaser();
        $this->createIncome();
        $this->createReviewSlot();
    }

    private function resizeVideo()
    {
        $newName = pathinfo($this->video->name, PATHINFO_FILENAME).'.mp4';
        FFMpeg::fromDisk('original_videos')
            ->open($this->video->name)
            ->addFilter(function (VideoFilters $filters) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension(576, 1024));
            })
            ->export()
            ->toDisk('videos')
            ->inFormat(new \FFMpeg\Format\Video\X264)
            ->save($newName);

        if (pathinfo($this->video->name, PATHINFO_EXTENSION) !== 'mp4') {
            Video::find($this->video->id)->update(['name' => $newName]);
            $this->video->name = $newName;
        }
    }

    private function createThumbnail()
    {
        FFMpeg::fromDisk('videos')
            ->open($this->video->name)
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('thumbnails')
            ->save($this->video->thumbnail);
    }

    private function updateVideoAsComplete()
    {
        Video::find($this->video->id)->update(['processing_complete' => true]);
    }

    private function sendEmailToPurchaser()
    {
        $purchaserEmail = DB::table('orders')
            ->where('id', $this->video->order_id)
            ->pluck('purchaser_email');

        Mail::to($purchaserEmail)->send(new VideoMail($this->video->name, $this->user->nick));
    }

    private function createIncome()
    {
        $grossAmount = DB::table('orders')
            ->where('orders.id', $this->video->order_id)
            ->join('offers', 'offers.id', '=', 'orders.offer_id')
            ->value('offers.price');

        $order = Order::find($this->video->order_id);

        $createdAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at);
        $deadline = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->deadline);

        $commission = \Config::get('constans.commission');
        
        if ($createdAt->diffInDays($deadline) === 1) {
            $commission = \Config::get('constans.commission_if_delivery_in_24h');
        }

        $netAmount = (float)$grossAmount*(1-$commission);

        Log::info($netAmount);

        Income::create([
            'user_id' => $this->user->id,
            'net_amount' => $netAmount,
            'order_id' => $this->video->order_id
        ]);
    }

    private function createReviewSlot()
    {
        Review::create([
            'video_id' => $this->video->id,
            'access_key' => Str::random(60)
        ]);
    }

    public function failed(Throwable $exception)
    {
        Video::find($this->video->id)->delete();
        Income::where('order_id', $this->video->order_id)->delete();
        Review::where('video_id', $this->video->id)->delete();

        Log::error("Job video processing fail. $exception");
        Log::error("failed video id: ".$this->video->id.", order_id: ".$this->video->order_id);
    }
}
