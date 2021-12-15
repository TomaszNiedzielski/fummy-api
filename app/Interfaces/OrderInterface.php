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
}