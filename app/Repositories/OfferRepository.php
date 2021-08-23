<?php

namespace App\Repositories;

use App\Interfaces\OfferInterface;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use DB;

class OfferRepository implements OfferInterface
{
    public function update(OfferRequest $request) {
        $data = $request->offerData;

        DB::table('offers')
            ->where('user_id', auth()->user()->id)
            ->delete();

        foreach($data as $key=>$item) {
            DB::table('offers')
                ->insert(
                    ['user_id' => auth()->user()->id, 'title' => $item['title'], 'price' => $item['price'], 'currency' => $item['currency'], 'created_at' => date('Y-m-d H:i:s')]
                );
        }

        return $data;
    }

    public function load(string $nick) {
        $offers = DB::table('users')
            ->where('users.nick', $nick)
            ->join('offers', 'offers.user_id', '=', 'users.id')
            ->select('offers.id', 'offers.title', 'offers.price', 'offers.currency')
            ->get();

        return $offers;
    }
}