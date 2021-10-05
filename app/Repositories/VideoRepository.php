<?php

namespace App\Repositories;

use App\Interfaces\VideoInterface;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use DB;
use Illuminate\Support\Str;

class VideoRepository implements VideoInterface
{
    public function upload(VideoRequest $request) {
        $video = $request->file('video');
        $videoNameWithExt = $video->getClientOriginalName();
        $videoName = pathinfo($videoNameWithExt, PATHINFO_FILENAME);
        $extension = $video->guessExtension();
        $newVideoNameWithoutExt = Str::random(30);
        $videoNameToStore = $newVideoNameWithoutExt.'.'.$extension;
        $path = $video->storeAs('public/original_videos', $videoNameToStore);

        $video = new Video;
        $video->user_id = auth()->user()->id;
        $video->name = $videoNameToStore;
        $video->thumbnail = $newVideoNameWithoutExt.'.png';
        $video->order_id = $request->orderId;
        $video->save();

        return (object) ['status' => 'success', 'message' => 'Video zostało zapisane.', 'video' => $video];
    }

    public function getList(string $nick) {
        $videos = DB::table('users')
            ->where('nick', $nick)
            ->join('videos', function($join) {
                $join->on('videos.user_id', '=', 'users.id')
                ->where('processing_complete', true);
            })
            ->join('orders', function($join) {
                $join->on('orders.id', '=', 'videos.order_id')
                ->where('is_private', false);
            })
            ->select('videos.name', 'videos.thumbnail')
            ->orderBy('videos.created_at', 'desc')
            ->get();

        return $videos;
    }
}