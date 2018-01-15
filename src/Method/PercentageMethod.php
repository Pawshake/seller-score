<?php

namespace Pawshake\SellerScore\Method;

class PercentageMethod implements CalculationMethod
{
    /**
     * @var int
     */
    private $maximumTotal;

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE_PERCENTAGE;
    }

    /**
     * @param int $maximumTotal
     */
    public function __construct($maximumTotal)
    {
        $this->maximumTotal = $maximumTotal;
    }

    /**
     * @return int
     */
    public function getMaximumTotal()
    {
        return $this->maximumTotal;
    }
}
