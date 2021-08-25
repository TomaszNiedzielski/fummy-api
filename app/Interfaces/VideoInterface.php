<?php

namespace App\Interfaces;

use App\Http\Requests\VideoRequest;

interface VideoInterface
{
    /**
     * Upload video
     * 
     * @method  POST    api/video/upload
     * @access  public
     */
    public function upload(VideoRequest $request);

    /**
     * Get videos list
     * 
     * @method  GET     api/videos/get-list/{nick}
     * @access  public
     */
    public function getList(string $nick);
}