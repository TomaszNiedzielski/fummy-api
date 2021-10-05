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
use App\Models\Video;

class VideoProcessingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private object $video;
    private string $userNick;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(object $video, string $userNick)
    {
        $this->video = $video;
        $this->userNick = $userNick;
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

        Video::find($this->video->id)->update(['processing_complete' => true]);

        $this->sendEmailToPurchaser();
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
            ->save($this->video->name);
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

    private function sendEmailToPurchaser() {
        $purchaserEmail = DB::table('orders')
            ->where('id', $this->video->order_id)
            ->pluck('purchaser_email');

        Mail::to($purchaserEmail)->send(new VideoMail($this->video->name, $this->userNick));
    }
}
