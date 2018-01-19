<?php

namespace Pawshake\SellerScore;

use Pawshake\SellerScore\Calculation;
use ReflectionClass;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check for syntax errors.
     */
    public function testCalculator()
    {
        $calculationsCollection = new CalculationsCollection();

        $calculationsCollection
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation',
                    'timeframe',
                    10,
                    100
                ), 100, 100)
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation 2',
                    'timeframe',
                    10,
                    100
                ), 100, 100)
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation 3',
                    'timeframe',
                    10,
                    100
                ), 10, 10); // Only 10 records, 10 out of 10 = 10 points

        /** @var Calculator $calculator */
        $calculator = $this->getMockForAbstractClass('Pawshake\SellerScore\Calculator');

        // Set the calculation collection.
        $reflection = new ReflectionClass($calculator);
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);

        $reflection_property->setValue($calculator, $calculationsCollection);

        $result = $calculator->calculate(0);

        $this->assertEquals(30, $result);
    }
}
