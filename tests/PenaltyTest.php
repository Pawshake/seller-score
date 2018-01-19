<?php

namespace Pawshake\SellerScore;

class PenaltyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $threshold
     * @param $comparison
     * @param $penalty
     * @param $penaltyOperation
     * @param $input
     * @param bool $applies
     *
     * @dataProvider penaltyProvider
     */
    public function testPenalty($threshold, $comparison, $input, $penalty, $penaltyOperation, $applies) {
        $penalty = new Penalty($threshold, $comparison, $penalty, $penaltyOperation);
        $this->assertEquals($applies, $penalty->applies($input));
    }

    public function penaltyProvider()
    {
        // threshold, comparison, input, penalty, penaltyOperation, applies
        return [
            [10, Penalty::COMPARISON_BIGGER, 15, 0.5, Penalty::OPERATION_MULTIPLY, true],
            [20, Penalty::COMPARISON_BIGGER, 15, 0.5, Penalty::OPERATION_MULTIPLY, false],
            [10, Penalty::COMPARISON_BIGGER, 20, 100, Penalty::OPERATION_PLUS, true],
            [20, Penalty::COMPARISON_BIGGER, 20, 100, Penalty::OPERATION_PLUS, false],
            [10, Penalty::COMPARISON_BIGGER, 20, -10000, Penalty::OPERATION_PLUS, true],
            [20, Penalty::COMPARISON_SMALLER, 15, 0.5, Penalty::OPERATION_MULTIPLY, true],
            [10, Penalty::COMPARISON_SMALLER, 20, 0.5, Penalty::OPERATION_MULTIPLY, false],
            [10, Penalty::COMPARISON_SMALLER, 20, 100, Penalty::OPERATION_PLUS, false],
            [10, Penalty::COMPARISON_SMALLER, 5, 100, Penalty::OPERATION_PLUS, true],
            [10, Penalty::COMPARISON_SMALLER, 5, -10000, Penalty::OPERATION_PLUS, true],
        ];
    }
}
