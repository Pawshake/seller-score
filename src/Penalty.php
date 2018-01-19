<?php

namespace Pawshake\SellerScore;

class Penalty
{
    CONST OPERATION_PLUS = '+';
    CONST OPERATION_MULTIPLY = '*';
    const OPERATION_FIXED_VALUE = 'fixed_value';

    CONST COMPARISON_BIGGER = '>';
    CONST COMPARISON_SMALLER = '<';

    /**
     * @var int
     */
    private $threshold;

    /**
     * @var string
     */
    private $comparison;

    /**
     * @var float
     */
    private $penalty;

    /**
     * @var string
     */
    private $penaltyOperation;

    /**
     * @param int $threshold Total to compare to.
     * @param string $comparison Comparison method.
     * @param int $penalty The penalty when it matches.
     * @param string $penaltyOperation The operation for the penalty on the input.
     */
    public function __construct($threshold, $comparison, $penalty, $penaltyOperation)
    {
        $this->threshold = $threshold;
        $this->comparison = $comparison;

        $this->penalty = $penalty;
        $this->penaltyOperation = $penaltyOperation;
    }

    public function applies($input) {
        return (static::COMPARISON_BIGGER === $this->comparison && $input > $this->threshold)
            || (static::COMPARISON_SMALLER === $this->comparison && $input < $this->threshold);
    }

    /**
     * @param int $input The input for calculating the penalty.
     * @param int $points The points to use for calculating the penalty.
     *
     * @return int
     */
    public function calculate($input, $points)
    {
        if ($this->matches($input)) {
            return $this->calculatePenalty($points);
        }

        return $points;
    }

    /**
     * @param int $input
     * @return bool
     */
    public function matches($input) {
        return (static::COMPARISON_BIGGER === $this->comparison && $input > $this->threshold)
            || (static::COMPARISON_SMALLER === $this->comparison && $input < $this->threshold);
    }

    /**
     * @param int $input
     * @return string
     */
    public function getDescription($input) {
        return $input . ' ' . $this->comparison . ' ' . $this->threshold
        . ' = <points> ' . $this->penaltyOperation . ' ' . $this->penalty;
    }

    /**
     * @param $points
     *
     * @return int
     */
    public function calculatePenalty($points) {
        switch ($this->penaltyOperation) {
            case static::OPERATION_FIXED_VALUE:
                $points = $this->penalty;
                break;

            case static::OPERATION_MULTIPLY:
                $points *= $this->penalty;
                break;

            case static::OPERATION_PLUS:
                $points += $this->penalty;
                break;
        }

        return (int) round($points);
    }
}
