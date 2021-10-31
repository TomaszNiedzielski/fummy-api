<?php

namespace App\Http\Controllers;

use App\Interfaces\SearchInterface;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;

class SearchController extends Controller
{
    use ResponseAPI;

    protected $searchInterface;

    public function __construct(SearchInterface $searchInterface) {
        $this->searchInterface = $searchInterface;
    }

    public function search(Request $request) {
        $response = $this->searchInterface->search($request->searchingWord);

        return $this->success($response);
    }

    public function getVerifiedUsers() {
        $response = $this->searchInterface->getVerifiedUsers();

        return $this->success($response);
    }
}