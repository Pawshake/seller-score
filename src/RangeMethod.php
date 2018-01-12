<?php

namespace Pawshake\SellerScore;

class RangeMethod implements CalculationMethod
{
    /**
     * @var int
     */
    private $from;

    /**
     * @var int
     */
    private $to;

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE_RANGE;
    }

    /**
     * @param int $from
     * @param int $to
     */
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return int
     */
    public function getTo()
    {
        return $this->to;
    }
}
