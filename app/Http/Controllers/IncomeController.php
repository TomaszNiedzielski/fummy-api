<?php

namespace App\Http\Controllers;

use App\Interfaces\IncomeInterface;
use App\Traits\ResponseAPI;

class IncomeController extends Controller
{
    use ResponseAPI;
    
    protected $incomeInterface;

    public function __construct(IncomeInterface $incomeInterface)
    {
        $this->incomeInterface = $incomeInterface;
    }

    public function getIncomesHistory()
    {
        $response = $this->incomeInterface->getIncomesHistory();

        return $this->success($response);
    }

    public function getIncome()
    {
        $response = $this->incomeInterface->getIncome();

        return $this->success($response);
    }
}
