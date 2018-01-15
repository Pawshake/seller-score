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
     * @param int $total
     */
    public function __construct($total)
    {
        $this->maximumTotal = $total;
    }

    /**
     * @return int
     */
    public function getMaximumTotal()
    {
        return $this->maximumTotal;
    }
}
