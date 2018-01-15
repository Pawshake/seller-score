<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\Method\RangeMethod;
use Pawshake\SellerScore\Penalty;

class RangeCalculation extends Calculation
{
    /**
     * @var RangeMethod
     */
    protected $calculationMethod;

    /**
     * @param string $name
     * @param string $timeframe
     * @param int $points Maximum mount of points this calculation is worth.
     * @param int $from
     * @param int $to
     * @param string|null $unit
     * @param Penalty|null $softPenalty
     * @param Penalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        $from,
        $to,
        $unit = null,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, new RangeMethod($from, $to, $unit), $softPenalty, $hardPenalty);
    }

    /**
     * @param int $input
     * @param int|null $maximumTotal
     * @return int
     */
    protected function calculatePoints($input, $maximumTotal = null)
    {
        $range = $this->calculationMethod->getTo() - $this->calculationMethod->getFrom();
        $correctedStartValue = $input - $this->calculationMethod->getFrom();
        $percentage = ($correctedStartValue * 100) / $range;
        $percentage = $percentage > 100 ? 100 : $percentage;

        return (int) round($percentage * ($this->points / 100));
    }
}
