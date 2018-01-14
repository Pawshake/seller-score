<?php

namespace Pawshake\SellerScore;

class PenaltyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider penaltyProvider
     */
    public function testPenalty($amount, $comparison, $penalty, $penaltyOperation, $input, $currentPoints, $expectedPoints)
    {
        $penalty = new Penalty($amount, $comparison, $penalty, $penaltyOperation);

        $result = $penalty->calculate($input, $currentPoints);

        $this->assertEquals($expectedPoints, $result);
    }

    public function penaltyProvider()
    {
        // amount, comparison, penalty, penaltyOperation, input, currentPoints, expectedPoints.
        return [
            [10, Penalty::COMPARISON_BIGGER, 0.5, Penalty::OPERATION_MULTIPLY, 15, 100, 50],
            [20, Penalty::COMPARISON_BIGGER, 0.5, Penalty::OPERATION_MULTIPLY, 15, 100, 100],
            [10, Penalty::COMPARISON_BIGGER, 100, Penalty::OPERATION_PLUS, 20, 100, 200],
            [20, Penalty::COMPARISON_BIGGER, 100, Penalty::OPERATION_PLUS, 20, 100, 100],
            [10, Penalty::COMPARISON_BIGGER, -10000, Penalty::OPERATION_PLUS, 20, 7000, -3000],
            [20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY, 15, 100, 50],
            [10, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY, 20, 100, 100],
            [10, Penalty::COMPARISON_SMALLER, 100, Penalty::OPERATION_PLUS, 20, 100, 100],
            [10, Penalty::COMPARISON_SMALLER, 100, Penalty::OPERATION_PLUS, 5, 100, 200],
            [10, Penalty::COMPARISON_SMALLER, -10000, Penalty::OPERATION_PLUS, 5, 100, -9900],
        ];
    }
}
