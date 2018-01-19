<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\HardPenalty;
use Pawshake\SellerScore\Penalty;

class CountdownCalculation extends Calculation
{
    /**
     * @var int
     */
    protected $iterate;

    /**
     * @param string $name
     * @param string $timeframe
     * @param int $points Maximum mount of points this calculation is worth.
     * @param int $iterate
     * @param Penalty|null $softPenalty
     * @param HardPenalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        $iterate,
        Penalty $softPenalty = null,
        HardPenalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, $softPenalty, $hardPenalty);
        $this->iterate = $iterate;
    }

    protected function convertInput($input, $total = null)
    {
        return $input;
    }

    /**
     * @param int $input
     * @return int
     */
    protected function calculatePoints($input)
    {
        $pointsToSubtract = $input * $this->iterate;

        if ($pointsToSubtract < $this->points) { // Don't add less than 0.
            return (int)round($this->points - $pointsToSubtract);
        }

        return 0;
    }
}
