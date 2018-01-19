<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\Penalty;

class RangeCalculation extends PercentageCalculation
{
    /**
     * @var int
     */
    protected $from;

    /**
     * @var int
     */
    protected $to;

    /**
     * @var string
     */
    protected $unit;

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
        parent::__construct($name, $timeframe, $points, 0, $softPenalty, $hardPenalty);
        $this->from = $from;
        $this->to = $to;
        $this->unit = $unit;
    }

    /**
     * @inheritdoc
     */
    protected function calculatePoints($input, $total = null)
    {
        $range = $this->to - $this->from;
        $correctedStartValue = $input - $this->from;

        return parent::calculatePoints($correctedStartValue, $range);
    }
}
