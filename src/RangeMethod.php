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
     * @var string
     */
    private $unit;

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
     * @param string $unit
     */
    public function __construct($from, $to, $unit = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->unit = $unit;
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

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return string
     */
    public function getRangeDescription()
    {
        return $this->getFrom() . ' ' . $this->getUnit() . ' -> ' . $this->getTo() . ' ' . $this->getUnit();
    }
}
