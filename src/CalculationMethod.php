<?php

namespace Pawshake\SellerScore;

interface CalculationMethod
{
    CONST TYPE_PERCENTAGE = '%';
    CONST TYPE_RANGE = 'range';

    /**
     * @return string
     */
    public function getType();
}
