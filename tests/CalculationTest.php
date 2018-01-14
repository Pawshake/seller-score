<?php

namespace Pawshake\SellerScore\Calculations;

use Pawshake\SellerScore\CalculationResult;
use Pawshake\SellerScore\Penalty;
use Pawshake\SellerScore\PercentageMethod;
use Pawshake\SellerScore\RangeMethod;

class CalculationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider percentageProvider
     */
    public function testPercentageMethod($points, $total, $input, $expectedEarnedPoints)
    {
        $calculation = new LastCalendarUpdate(
            $points, new PercentageMethod($total)
        );

        $result = $calculation->calculate($input);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals($expectedEarnedPoints, $result->getPoints());
        $this->assertInternalType("int", $result->getPoints());
    }

    public function percentageProvider()
    {
        // Points, total, input, expected earned points
        return [
            [100, 100, 10, 10],
            [100, 100, 25, 25],
            [80, 100, 80, 64],
        ];
    }

    /**
     * @dataProvider rangeProvider
     */
    public function testRangeMethod($points, $from, $to, $input, $expectedEarnedPoints)
    {
        $calculation = new LastCalendarUpdate(
            $points, new RangeMethod($from, $to)
        );

        $result = $calculation->calculate($input);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals($expectedEarnedPoints, $result->getPoints());
        $this->assertInternalType("int", $result->getPoints());
    }

    public function rangeProvider()
    {
        // Points, from, to, input, expected earned points
        return [
            [100, 0, 100, 10, 10],
            [80, 0, 100, 20, 16],
            [20, 40, 7, 8, 19],
        ];
    }

    public function testInvalidInputType()
    {
        $calculation = new LastCalendarUpdate(
            100, new PercentageMethod(100)
        );

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Calculation input should be an integer');

        $calculation->calculate('this should fail');
    }

    /**
     * @dataProvider penaltyProvider
     */
    public function testCalculationWithPenalty($softPenalty, $hardPenalty, $expectedEarnedPoints)
    {
        $calculation = new LastCalendarUpdate(
            100,
            new PercentageMethod(200),
            $softPenalty,
            $hardPenalty
        );

        $result = $calculation->calculate(50);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals($expectedEarnedPoints, $result->getPoints());
        $this->assertInternalType("int", $result->getPoints());
    }

    public function penaltyProvider()
    {
        // softPenalty, hardPenalty, to, input, expected earned points
        return [
            [
                new Penalty(
                    60,
                    Penalty::COMPARISON_BIGGER,
                    0.5,
                    Penalty::OPERATION_MULTIPLY
                ),
                null,
                25,
            ],
            [
                new Penalty(
                    40,
                    Penalty::COMPARISON_BIGGER,
                    0.5,
                    Penalty::OPERATION_MULTIPLY
                ),
                null,
                13,
            ],
            [
                new Penalty(
                    60,
                    Penalty::COMPARISON_SMALLER,
                    0.2,
                    Penalty::OPERATION_MULTIPLY
                ),
                null,
                5,
            ],
            [
                new Penalty(
                    60,
                    Penalty::COMPARISON_SMALLER,
                    0.2,
                    Penalty::OPERATION_MULTIPLY
                ),
                new Penalty(
                    55,
                    Penalty::COMPARISON_SMALLER,
                    -10000,
                    Penalty::OPERATION_PLUS
                ),
                -9975,
            ],
            [
                null,
                new Penalty(
                    5,
                    Penalty::COMPARISON_BIGGER,
                    -10000,
                    Penalty::OPERATION_PLUS
                ),
                -9975,
            ],
        ];
    }
}
