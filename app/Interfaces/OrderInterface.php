<?php

namespace App\Interfaces;

use App\Http\Requests\OrderRequest;

interface OrderInterface
{
    /**
     * Create order
     * 
     * @method  POST  api/orders
     */
    public function makeOrders(OrderRequest $request);

    /**
     * Load orders for user
     * 
     * @method  GET  api/orders
     */
    public function getOrders();

    /**
     * Verify status of purchase (paid or unpaid)
     * \
     * @method  POST  api/orders/purchase/verify-status?purchase_key=[generated_key]
     */
    public function verifyPurchaseStatus(string $purchaseKey);
}