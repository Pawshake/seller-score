<?php

namespace Pawshake\SellerScore\Method;

class PercentageMethod implements CalculationMethod
{
    /**
     * @var int
     */
    private $total;

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
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }
}
