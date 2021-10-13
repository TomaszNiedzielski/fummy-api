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
use App\Models\{Video, Income};
use Illuminate\Support\Facades\Log;
use Throwable;

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
    }

    private function resizeVideo() {
        FFMpeg::fromDisk('original_videos')
            ->open($this->video->name)
            ->addFilter(function (VideoFilters $filters) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension(576, 1024));
            })
            ->export()
            ->toDisk('videos')
            ->inFormat(new \FFMpeg\Format\Video\X264)
            ->save(pathinfo($this->video->name, PATHINFO_FILENAME).'.mp4');
    }

    private function createThumbnail() {
        $name = pathinfo($this->video->name, PATHINFO_FILENAME).'.png';

        FFMpeg::fromDisk('original_videos')
            ->open($this->video->name)
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('thumbnails')
            ->save($name);

        return $name;
    }

    private function updateVideoAsComplete() {
        Video::find($this->video->id)->update(['processing_complete' => true]);
    }

    private function sendEmailToPurchaser() {
        $purchaserEmail = DB::table('orders')
            ->where('id', $this->video->order_id)
            ->pluck('purchaser_email');

        Mail::to($purchaserEmail)->send(new VideoMail($this->video->name, $this->user->nick));
    }

    private function createIncome() {
        $grossAmount = DB::table('orders')
            ->where('orders.id', $this->video->order_id)
            ->join('offers', 'offers.id', '=', 'orders.offer_id')
            ->pluck('offers.price');

        $commission = \Config::get('constans.commission');
        $netAmount = (float)$grossAmount[0]*(1-$commission);

        Log::info($netAmount);

        Income::create([
            'user_id' => $this->user->id,
            'net_amount' => $netAmount,
            'order_id' => $this->video->order_id
        ]);
    }

    public function failed(Throwable $exception) {

        Log::error("Job video processing fail. $exception");
    }
}
