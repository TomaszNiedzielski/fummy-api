<?php

namespace App\Interfaces;

use App\Http\Requests\OrderRequest;

interface OrderInterface
{
    /**
     * Create order
     * 
     * @method  api/order/create
     * @access  public
     */
    public function create(OrderRequest $request);

    /**
     * Load orders for user
     * 
     * @method  api/orders/load
     * @access  public
     */
    public function load();
}