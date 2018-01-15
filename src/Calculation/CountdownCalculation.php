<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\Method\CountdownMethod;
use Pawshake\SellerScore\Penalty;

class CountdownCalculation extends Calculation
{
    /**
     * @var CountdownMethod
     */
    protected $calculationMethod;

    /**
     * @param string $name
     * @param string $timeframe
     * @param int $points Maximum mount of points this calculation is worth.
     * @param int $start
     * @param int $iterate
     * @param Penalty|null $softPenalty
     * @param Penalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        $start,
        $iterate,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, new CountdownMethod($start, $iterate), $softPenalty, $hardPenalty);
    }

    /**
     * @param int $input
     * @param int|null $maximumTotal
     * @return int
     */
    protected function calculatePoints($input, $maximumTotal = null)
    {
        $pointsToAdd = $this->calculationMethod->getStart() - ($input * $this->calculationMethod->getIterate());
        if ($pointsToAdd > 0) { // Don't add less than 0.
            return (int)round($this->points + $pointsToAdd);
        }

        return $this->points;
    }
}
