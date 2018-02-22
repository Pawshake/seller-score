<?php

namespace Pawshake\SellerScore\Calculation;
use Pawshake\SellerScore\CalculationResult;
use Pawshake\SellerScore\ScoreInformation;

/**
 * Class RandomBoostCalculation
 * @package Pawshake\SellerScore\Calculation
 */
class RandomBoostCalculation extends Calculation
{
    /** @var int $min */
    protected $min;
    /** @var int $max */
    protected $max;

    /**
     * @param $name
     * @param $timeframe
     * @param $min
     * @param $max
     */
    public function __construct($name, $timeframe, $min, $max)
    {
        $this->min = $min;
        $this->max = $max;
        parent::__construct($name, $timeframe, $max);
    }

    /**
     * @inheritdoc
     */
    protected function setType()
    {
        return static::RANDOM_BOOST;
    }

    protected function convertInput($input, $total = null)
    {
        return $input;
    }

    public function calculate($input = 0, $total = null, $applyPenalties = true)
    {
        $pointsEarned = $this->calculatePoints($input);

        $scoreInformation = new ScoreInformation(
            $this->getDescription(),
            0,
            0,
            $this->max,
            $pointsEarned,
            null,
            null
        );

        return new CalculationResult(
            $pointsEarned,
            $scoreInformation,
            null,
            null
        );
    }

    /**
     * @inheritdoc
     */
    protected function comparePenaltiesWithConvertedInput()
    {
        return true;
    }

    protected function calculatePoints($input)
    {
        return mt_rand($this->min, $this->max);
    }
}
