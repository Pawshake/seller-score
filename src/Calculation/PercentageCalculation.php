<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\Penalty;
use Pawshake\SellerScore\PenaltyResult;

class PercentageCalculation extends Calculation
{
    /**
     * @var int
     */
    protected $maximumTotal;

    /**
     * @param string $name
     * @param string $timeframe
     * @param int $points Maximum mount of points this calculation is worth.
     * @param int $maximumTotal
     * @param Penalty|null $softPenalty
     * @param Penalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        $maximumTotal = 0,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, $softPenalty, $hardPenalty);
        $this->maximumTotal = $maximumTotal;
    }

    /**
     * @param int $input
     * @param int|null $total
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    protected function calculatePoints($input, $total = null)
    {
        $percentage = $this->calculatePercentage($input, $total);

        return (int) round($percentage * ($this->points / 100));
    }

    /**
     * @param int $input
     * @param int $pointsEarned
     * @param int|null $total
     *
     * @throws \InvalidArgumentException
     *
     * @return PenaltyResult
     */
    protected function calculatePenalty($input, $pointsEarned, $total = null)
    {
        $percentage = $this->calculatePercentage($input, $total);

        return parent::calculatePenalty($percentage, $pointsEarned);
    }

    /**
     * @param $input
     * @param $total
     *
     * @throws \InvalidArgumentException
     *
     * @return float|int
     */
    private function calculatePercentage($input, $total)
    {
        if (empty($total) && empty($this->maximumTotal))
        {
            return 0;
        }

        $total = empty($total) ? $this->maximumTotal : $total;

        $percentage = ($input / $total) * 100;

        $percentage = $percentage > 100 ? 100 : $percentage;
        $percentage = $percentage < 0 ? 0 : $percentage;

        return $percentage;
    }
}
