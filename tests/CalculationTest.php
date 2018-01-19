<?php

namespace Pawshake\SellerScore\Calculations;

use Pawshake\SellerScore\CalculationResult;
use Pawshake\SellerScore\Calculation;
use Pawshake\SellerScore\Penalty;

class CalculationTest extends \PHPUnit_Framework_TestCase
{
    const CALCULATION_NAME = 'Test Calculation';
    const CALCULATION_TIMEFRAME = 'timeframe';

    /**
     * @dataProvider percentageProvider
     */
    public function testPercentageMethod($points, $total, $input, $expectedEarnedPoints)
    {
        $calculation = new Calculation\PercentageCalculation(
            static::CALCULATION_NAME, static::CALCULATION_TIMEFRAME,
            $points
        );

        $result = $calculation->calculate($input, $total);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals($expectedEarnedPoints, $result->getPoints());
        $this->assertInternalType('int', $result->getPoints());
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
        $calculation = new Calculation\RangeCalculation(
            static::CALCULATION_NAME, static::CALCULATION_TIMEFRAME,
            $points, $from, $to
        );

        $result = $calculation->calculate($input);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals($expectedEarnedPoints, $result->getPoints());
        $this->assertInternalType('int', $result->getPoints());
    }

    public function rangeProvider()
    {
        // Points, from, to, input, expected earned points
        return [
            [100, 0, 100, 10, 10],
            [80, 0, 100, 20, 16],
            [20, 40, 7, 8, 19],
            [20, 40, 7, 7, 20],
            [20, 40, 7, 6, 20],
            [20, 40, 7, 1, 20],
            [20, 40, 7, 500, 0],
        ];
    }

    /**
     * @dataProvider countdownProvider
     */
    public function testCountdownMethod($points, $iterate, $input, $expectedEarnedPoints)
    {
        $calculation = new Calculation\CountdownCalculation(
            static::CALCULATION_NAME, static::CALCULATION_TIMEFRAME,
            $points, $iterate
        );

        $result = $calculation->calculate($input);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals($expectedEarnedPoints, $result->getPoints());
        $this->assertInternalType('int', $result->getPoints());
    }

    public function countdownProvider()
    {
        // Points, iterate, input, expected earned points
        return [
            [600, 10, 10, 500],
            [600, 10, 20, 400],
            [600, 60, 10, 0],
            [600, 120, 100, 0],
        ];
    }

    public function testInvalidInputType()
    {
        $calculation = new Calculation\PercentageCalculation(
            static::CALCULATION_NAME, static::CALCULATION_TIMEFRAME,
            100, 100
        );

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Calculation input should be an integer');

        $calculation->calculate('this should fail');
    }

    /**
     * @param Calculation\Calculation $calculation
     * @param bool $softPenalty
     * @param bool $hardPenalty
     *
     * @dataProvider penaltyProvider
     */
    public function testCalculationWithPenalty(
        Calculation\Calculation $calculation,
        $softPenalty = false,
        $hardPenalty = false
    ) {
        $result = $calculation->calculate(50);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals($softPenalty, $result->hasSoftPenalty());
        $this->assertEquals($hardPenalty, $result->hasHardPenalty());
        $this->assertInternalType('int', $result->getPoints());
    }

    public function penaltyProvider()
    {
        // calculation, soft penalty, hard penalty
        return [
            [
                new Calculation\PercentageCalculation('Test Calculation',
                    'timeframe',
                    100,
                    200,
                    new Penalty(
                        60,
                        Penalty::COMPARISON_BIGGER,
                        0.5,
                        Penalty::OPERATION_MULTIPLY
                    ),
                    null
                ),
                false,
                false,
            ],
            [
                new Calculation\PercentageCalculation('Test Calculation',
                    'timeframe',
                    100,
                    200,
                    new Penalty(
                        40,
                        Penalty::COMPARISON_BIGGER,
                        0.5,
                        Penalty::OPERATION_MULTIPLY
                    ),
                    null
                ),
                false,
                false,
            ],
            [
                new Calculation\PercentageCalculation('Test Calculation',
                    'timeframe',
                    100,
                    200,
                    new Penalty(
                        60,
                        Penalty::COMPARISON_SMALLER,
                        0.2,
                        Penalty::OPERATION_MULTIPLY
                    ),
                    null
                ),
                true,
                false,
            ],
            [
                new Calculation\RangeCalculation('Test Calculation',
                    'timeframe',
                    100,
                    1,
                    100,
                    '',
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
                    )
                ),
                true,
                true,
            ],
            [
                new Calculation\PercentageCalculation('Test Calculation',
                    'timeframe',
                    100,
                    200,
                    null,
                    new Penalty(
                        5,
                        Penalty::COMPARISON_BIGGER,
                        -10000,
                        Penalty::OPERATION_PLUS
                    )
                ),
                false,
                true,
            ],
        ];
    }
}
