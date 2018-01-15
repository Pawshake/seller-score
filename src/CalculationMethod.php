<?php

namespace Pawshake\SellerScore;

interface CalculationMethod
{
    CONST TYPE_PERCENTAGE = '%';
    CONST TYPE_RANGE = 'range';
    CONST TYPE_COUNTDOWN = 'countdown';

    /**
     * @return string
     */
    public function getType();
}
