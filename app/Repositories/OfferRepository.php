<?php

namespace App\Repositories;

use App\Interfaces\OfferInterface;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use DB;

class OfferRepository implements OfferInterface
{
    public function update(OfferRequest $request) {
        $newOffers = $request->offers;

        $oldOffers = $this->load(auth()->user()->nick);

        foreach($newOffers as $newOffer) {
            $offerToSave = [
                'user_id' => auth()->user()->id,
                'title' => $newOffer['title'],
                'description' => $newOffer['description'],
                'price' => $newOffer['price'],
                'currency' => $newOffer['currency']
            ];

            if(!empty($newOffer['id'])) {
                /**
                 * Item is old because has ID
                 * 
                 * We fetching matching model from database
                 */

                $matchingOffer = Offer::where([
                    'id' => $newOffer['id'],
                    'user_id' => auth()->user()->id,
                    'is_removed' => false
                ])->first();

                if(!empty($matchingOffer)) {
                    /**
                     * If item was returned it means that the new one is the same that has been existing in database
                     * 
                     * So we need to check if title or price has been edited
                     */

                    if($matchingOffer->title === $newOffer['title'] && $matchingOffer->price === $newOffer['price'] && $matchingOffer->description === $newOffer['description']) {
                        // It means that this offer was not edited and we can go to check the next one
                        continue;
                    }

                    /**
                     * It means that offer was edited so we need to mark the old one as removed
                     */
                    $matchingOffer->is_removed = true;
                    $matchingOffer->save();

                    /**
                     *  And then create the new one
                     */
                    Offer::create($offerToSave);
                }
            } else {
                /**
                 * If this is new item
                 */

                Offer::create($offerToSave);
            }
        }

        /**
         * Check if any offer was removed
         */

        /**
         * If the new version doesn't contain some element of old version
         * this old version element needs to be removed
         */

        foreach($oldOffers as $oldOffer) {
            $isSet = false;

            foreach($newOffers as $newOffer) {
                if($newOffer['id'] === $oldOffer->id) {
                    $isSet = true;
                }
            }
            
            if(!$isSet) {
                /**
                 * The old one is not set in the new set so needs to be removed
                 */

                Offer::where([
                    'id' => $oldOffer->id,
                    'user_id' => auth()->user()->id
                ])->update([
                    'is_removed' => true
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
            ->select('offers.id', 'offers.title', 'offers.description', 'offers.price', 'offers.currency')
            ->get();

        return $offers;
    }
}