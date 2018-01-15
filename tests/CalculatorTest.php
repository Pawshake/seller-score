<?php

namespace Pawshake\SellerScore;

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
                new Calculation(
                    'Test Calculation',
                    'timeframe',
                    10,
                    new PercentageMethod(100)
                ), 100)
            ->addCalculation(
                new Calculation(
                    'Test Calculation 2',
                    'timeframe',
                    10,
                    new PercentageMethod(100)
                ), 100);

        $result = $calculator->calculateCollection($calculationsCollection);

        $this->assertEquals(20, $result);
    }
}
