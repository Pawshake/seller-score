<?php

namespace Pawshake\SellerScore\Method;

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
