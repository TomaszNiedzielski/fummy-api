<?php

namespace App\Repositories;

use App\Interfaces\VideoInterface;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use DB;

class VideoRepository implements VideoInterface
{
    public function upload(VideoRequest $request) {
        $video = $request->file('video');
        $videoNameWithExt = $video->getClientOriginalName();
        $videoName = pathinfo($videoNameWithExt, PATHINFO_FILENAME);
        $extension = $video->guessExtension();
        $videoNameToStore = $videoName.'_'.time().mt_rand( 0, 0xffff ).'.'.$extension;
        $path = $video->storeAs('public/videos', $videoNameToStore);

        $video = new Video;
        $video->user_id = auth()->user()->id;
        $video->name = $videoNameToStore;
        $video->thumbnail = $this->createThumbnailFrom($videoNameToStore);
        $video->order_id = $request->orderId;
        $video->save();

        return (object) ['status' => 'success', 'message' => 'Video zostało zapisane.'];
    }

    protected function createThumbnailFrom(string $videoName):string {
        $name = pathinfo($videoName, PATHINFO_FILENAME).'.png';

        FFMpeg::fromDisk('videos')
            ->open($videoName)
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('thumbnails')
            ->save($name);

        return $name;
    }

    public function getList(string $nick) {
        $videos = DB::table('users')
            ->where('nick', $nick)
            ->join('videos', 'videos.user_id', '=', 'users.id')
            ->join('orders', function($join) {
                $join->on('orders.id', '=', 'videos.order_id')
                ->where('is_private', false);
            })
            ->select('videos.name', 'videos.thumbnail')
            ->get();

        return $videos;
    }
}