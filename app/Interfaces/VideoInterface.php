<?php

namespace App\Interfaces;

use App\Http\Requests\VideoRequest;
use Illuminate\Http\Request;

interface VideoInterface
{
    /**
     * Upload video
     * 
     * @method  POST  api/videos
     */
    public function uploadVideos(VideoRequest $request);

    /**
     * Get videos list
     * 
     * @method  GET  api/videos
     */
    public function getVideos(Request $request);
}