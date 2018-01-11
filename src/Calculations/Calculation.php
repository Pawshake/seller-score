<?php

namespace Pawshake\SellerScore\Calculations;

use Pawshake\SellerScore\CalculationResult;

interface Calculation
{
    /**
     * @return CalculationResult
     */
    public function calculate();
}
