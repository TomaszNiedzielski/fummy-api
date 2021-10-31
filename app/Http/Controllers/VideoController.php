<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VideoRequest;
use App\Interfaces\VideoInterface;
use App\Traits\ResponseAPI;
use App\Jobs\VideoProcessingJob;

class VideoController extends Controller
{
    use ResponseAPI;

    protected $videoInterface;

    public function __construct(VideoInterface $videoInterface) {
        $this->videoInterface = $videoInterface;
    }

    public function upload(VideoRequest $request) {
        $response = $this->videoInterface->upload($request);

        if($response->code !== 200) {
            return $this->error();
        }

        $this->dispatch(new VideoProcessingJob($response->video, auth()->user()));

        return $this->success($response->message);
    }

    public function getList(string $nick) {
        $response = $this->videoInterface->getList($nick);

        return $this->success($response);
    }
}
