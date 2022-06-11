<?php

namespace App\Repositories;

use App\Interfaces\VideoInterface;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoRepository implements VideoInterface
{
    public function uploadVideos(VideoRequest $request)
    {
        $video = $request->file('video');
        $extension = $video->guessExtension();
        $newVideoNameWithoutExt = Str::random(30);
        $videoNameToStore = $newVideoNameWithoutExt.'.'.$extension;
        $video->storeAs('public/original_videos', $videoNameToStore);

        $video = new Video;
        $video->user_id = auth()->user()->id;
        $video->name = $videoNameToStore;
        $video->thumbnail = $newVideoNameWithoutExt.'.png';
        $video->order_id = $request->orderId;
        $video->save();

        return (object) ['code' => 200, 'message' => 'Video zostało zapisane.', 'video' => $video];
    }

    public function getVideos(Request $request)
    {
        $userNick = $request->query('user_nick');

        if (!$userNick) {
            return (object) ['code' => 400, 'message' => 'Brak informacji o użytkowniku.'];
        }

        $videos = DB::table('users')
            ->where('nick', $userNick)
            ->join('videos', function ($join) {
                $join->on('videos.user_id', '=', 'users.id')
                ->where('processing_complete', true);
            })
            ->join('orders', function ($join) {
                $join->on('orders.id', '=', 'videos.order_id')
                ->where('is_private', false);
            })
            ->select('videos.name', 'videos.thumbnail')
            ->orderBy('videos.created_at', 'desc')
            ->get();

        return (object) ['code' => 200, 'data' => $videos];
    }
}