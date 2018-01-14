<?php

namespace Pawshake\SellerScore;

class Penalty
{
    CONST OPERATION_PLUS = '+';
    CONST OPERATION_MULTIPLY = '*';

    CONST COMPARISON_BIGGER = '>';
    CONST COMPARISON_SMALLER = '<';

    /**
     * @var int
     */
    private $amount;

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
     * @param int $amount Total to compare to.
     * @param string $comparison Comparison method.
     * @param int $penalty The penalty when it matches.
     * @param string $penaltyOperation The operation for the penalty on the input.
     */
    public function __construct($amount, $comparison, $penalty, $penaltyOperation)
    {
        $this->amount = $amount;
        $this->comparison = $comparison;
        $this->penalty = $penalty;
        $this->penaltyOperation = $penaltyOperation;
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
        return (static::COMPARISON_BIGGER === $this->comparison && $input > $this->amount)
            || (static::COMPARISON_SMALLER === $this->comparison && $input < $this->amount);
    }

    /**
     * @param int $input
     * @return string
     */
    public function getDescription($input) {
        return $input . ' ' . $this->comparison . ' ' . $this->amount
        . ' = <points> ' . $this->penaltyOperation . ' ' . $this->penalty;
    }

    /**
     * @param int $points
     *
     * @return int
     */
    private function calculatePenalty($points) {
        if ($this->penaltyOperation === static::OPERATION_MULTIPLY) {
            return (int) round($points * $this->penalty);
        }

        return (int) round($points + $this->penalty);
    }
}
