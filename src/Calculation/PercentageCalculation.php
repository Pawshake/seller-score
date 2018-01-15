<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\Method\PercentageMethod;
use Pawshake\SellerScore\Penalty;
use Pawshake\SellerScore\PenaltyResult;

class PercentageCalculation extends Calculation
{
    /**
     * @var PercentageMethod
     */
    protected $calculationMethod;

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
        $maximumTotal = null,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, new PercentageMethod($maximumTotal), $softPenalty, $hardPenalty);
    }

    /**
     * @param int $input
     * @param int|null $total
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
     * @return PenaltyResult
     */
    protected function calculatePenalty($input, $pointsEarned, $total = null)
    {
        $percentage = $this->calculatePercentage($input, $total);

        return parent::calculatePenalty($percentage, $pointsEarned);
    }

    /**
     * @param int $input
     * @param int $total
     * @return float|int
     */
    private function calculatePercentage($input, $total)
    {
        $maximumTotal = $this->calculationMethod->getMaximumTotal();

        if (null === $total && $maximumTotal === null)
        {
            throw new \InvalidArgumentException('Total can not be null.');
        }

        $total = $total === null ? $maximumTotal : $total; // Fallback tot maximum total.

        if (null !== $maximumTotal && $total > $maximumTotal)
        {
            throw new \InvalidArgumentException('The input is for more than maximum allowed items.');
        }

        $percentage = ($input / $total) * 100;

        return $percentage > 100 ? 100 : $percentage;
    }
}
