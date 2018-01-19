<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\HardPenalty;
use Pawshake\SellerScore\Penalty;

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
     * @param HardPenalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        $maximumTotal = 0,
        Penalty $softPenalty = null,
        HardPenalty $hardPenalty = null
    ) {
        parent::__construct($name, $timeframe, $points, $softPenalty, $hardPenalty);
        $this->maximumTotal = $maximumTotal;
    }

    /**
     * @inheritdoc
     */
    protected function convertInput($input, $total = null)
    {
        return $this->calculatePercentage($input, $total);
    }

    /**
     * @inheritdoc
     */
    protected function comparePenaltiesWithConvertedInput()
    {
        return true;
    }

    /**
     * @param int $input
     *
     * @return int
     */
    protected function calculatePoints($input)
    {
        return (int) round($input * ($this->points / 100));
    }

    /**
     * @param $input
     * @param $total
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
