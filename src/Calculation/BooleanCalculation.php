<?php

namespace Pawshake\SellerScore\Calculation;
use Pawshake\SellerScore\HardPenalty;
use Pawshake\SellerScore\Penalty;

/**
 * Class BooleanCalculation
 * @package Pawshake\SellerScore\Calculation
 */
class BooleanCalculation extends Calculation {

    protected $value;

    public function __construct($name)
    {
        parent::__construct(
            $name,
            '',
            0,
            null,
            new HardPenalty(0, Penalty::COMPARISON_BIGGER)
        );
    }

    protected function comparePenaltiesWithConvertedInput()
    {
        return false;
    }

    protected function convertInput($input, $total = null)
    {
        return $input;
    }

    protected function setType()
    {
        return static::BOOLEAN;
    }

    protected function calculatePoints($input)
    {
        return $input;
    }
}