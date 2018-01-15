<?php

namespace Pawshake\SellerScore;


class PercentageCalculation extends Calculation
{
    /**
     * @param string $name
     * @param string $timeframe
     * @param int $points Maximum mount of points this calculation is worth.
     * @param int $total
     * @param Penalty|null $softPenalty
     * @param Penalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        $total,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, new PercentageMethod($total), $softPenalty, $hardPenalty);
    }

    /**
     * @param int $input
     * @param int|null $maximumTotal
     * @return int
     */
    protected function calculatePoints($input, $maximumTotal = null)
    {
        $percentage = $this->calculatePercentage($input, $maximumTotal);

        return (int) round($percentage * ($this->points / 100));
    }

    /**
     * @param int $input
     * @param int $pointsEarned
     * @param int|null $maximumTotal
     *
     * @return PenaltyResult
     */
    protected function calculatePenalty($input, $pointsEarned, $maximumTotal = null)
    {
        $percentage = $this->calculatePercentage($input, $maximumTotal);

        return parent::calculatePenalty($percentage, $pointsEarned);
    }

    /**
     * @param int $input
     * @param int|null $maximumTotal
     * @return float|int
     */
    private function calculatePercentage($input, $maximumTotal = null)
    {
        $total = $this->calculationMethod->getTotal();
        $total = null !== $maximumTotal && $maximumTotal < $total ? $maximumTotal : $total;

        $percentage = ($input / $total) * 100;

        return $percentage > 100 ? 100 : $percentage;
    }
}
