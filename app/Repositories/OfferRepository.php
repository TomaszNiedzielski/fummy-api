<?php

namespace App\Repositories;

use App\Interfaces\OfferInterface;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use DB;

class OfferRepository implements OfferInterface
{
    public function update(OfferRequest $request) {
        $offer = $request->offerData;

        foreach($offer as $item) {
            if(!empty($item['id'])) {
                /**
                 * If item has property isRemoved, remove from DB
                 */

                if(!empty($item['isRemoved'])) {
                    Offer::where([
                        'id' => $item['id'],
                        'user_id' => auth()->user()->id
                    ])->update([
                        'is_removed' => true
                    ]);

                    continue;
                }

                /**
                 * Check if this offer item exists
                 * If yes we cannot update him because someone could take a order on this offer
                 * So, we need to mark this one as a removed and create a new one.
                 *                 
                 * check if item was edited
                 */
                $offerItem = Offer::where([
                    'id' => $item['id'],
                    'user_id' => auth()->user()->id,
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'is_removed' => false
                ])->first();

                if(!empty($offerItem)) {
                    /**
                     * If item was returned it means that exist and was not edited
                     */
                    continue;
                }

                /**
                 * So we need to get this edited model by id and user_id
                 */

                $offerItem = Offer::where([
                    'id' => $item['id'],
                    'user_id' => auth()->user()->id,
                    'is_removed' => false
                ])->first();
                
                /**
                 * And mark it as removed
                 */

                $offerItem->is_removed = true;
                $offerItem->save();

                /**
                 * And create a new one
                 */
                Offer::create([
                    'user_id' => auth()->user()->id,
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'currency' => $item['currency']
                ]);
            } else {
                Offer::create([
                    'user_id' => auth()->user()->id,
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'currency' => $item['currency']
                ]);
            }
        }

        return $this->load(auth()->user()->nick);
    }

    public function load(string $nick) {
        $offers = DB::table('users')
            ->where('users.nick', $nick)
            ->join('offers', function($join) {
                $join->on('offers.user_id', '=', 'users.id')
                ->where('offers.is_removed', false);
            })
            ->select('offers.id', 'offers.title', 'offers.price', 'offers.currency')
            ->get();

        return $offers;
    }
}