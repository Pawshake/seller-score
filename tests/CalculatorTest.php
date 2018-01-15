<?php

namespace Pawshake\SellerScore;

use Pawshake\SellerScore\Calculation;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check for syntax errors.
     */
    public function testIsThereAnySyntaxError()
    {
        $var = new Calculator();
        $this->assertTrue(is_object($var));
    }

    /**
     * Check for syntax errors.
     */
    public function testCalculator()
    {
        $calculator = new Calculator();
        $calculationsCollection = new CalculationsCollection();

        $calculationsCollection
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation',
                    'timeframe',
                    10,
                    100
                ), 100)
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation 2',
                    'timeframe',
                    10,
                    100
                ), 100)
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation 3',
                    'timeframe',
                    10,
                    100
                ), 10, 10); // Only 10 records, 10 out of 10 = 10 points

        $result = $calculator->calculateCollection($calculationsCollection);

        $this->assertEquals(30, $result);
    }
}
