<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Interfaces\VideoInterface;
use App\Traits\ResponseAPI;
use App\Jobs\VideoProcessingJob;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use ResponseAPI;

    protected $videoInterface;

    public function __construct(VideoInterface $videoInterface)
    {
        $this->videoInterface = $videoInterface;
    }

    public function uploadVideos(VideoRequest $request)
    {
        $response = $this->videoInterface->uploadVideos($request);

        if ($response->code !== 200) {
            return $this->error();
        }

        $this->dispatch(new VideoProcessingJob($response->video, auth()->user()));

        return $this->success(null, $response->message); // Do poprawy kolejność
    }

    public function getVideos(Request $request)
    {
        $response = $this->videoInterface->getVideos($request);

        if ($response->code !== 200) {
            return $this->error($response->message, null, $response->code);
        }

        return $this->success($response->data);
    }
}
