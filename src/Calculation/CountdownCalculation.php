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
     * @param int $iterate
     * @param Penalty|null $softPenalty
     * @param Penalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        $iterate,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, new CountdownMethod($points, $iterate), $softPenalty, $hardPenalty);
    }

    /**
     * @param int $input
     * @param int|null $total
     * @return int
     */
    protected function calculatePoints($input, $total = null)
    {
        $pointsToSubtract = $input * $this->calculationMethod->getIterate();
        if ($pointsToSubtract < $this->points) { // Don't add less than 0.
            return (int)round($this->points - $pointsToSubtract);
        }

        return 0;
    }
}
