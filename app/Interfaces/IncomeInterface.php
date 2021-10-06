<?php

namespace App\Interfaces;

interface IncomeInterface
{
    /**
     * Show history of incomes
     * 
     * @method  api/income/get-history
     * @access  public
     */
    public function getIncomesHistory();

    /**
     * Count and return the income
     * 
     * @method  api/income/get
     * @access  public
     */
    public function getIncome();
}