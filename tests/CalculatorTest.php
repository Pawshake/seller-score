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

    public function testCalculatorWithSoftPenalty()
    {
        $calculationsCollection = new CalculationsCollection();

        $calculationsCollection
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation',
                    'timeframe',
                    10,
                    100,
                    new Penalty(20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY)
                ), 10, 100)
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
                ), 10, 10) // Only 10 records, 10 out of 10 = 10 points
            ->addCalculation(new Calculation\PercentageCalculation(
                'Test Calculation',
                'timeframe',
                10,
                100,
                new Penalty(20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY)
            ), 10, 100);

        /** @var Calculator $calculator */
        $calculator = $this->getMockForAbstractClass('Pawshake\SellerScore\Calculator');

        // Set the calculation collection.
        $reflection = new ReflectionClass($calculator);
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);

        $reflection_property->setValue($calculator, $calculationsCollection);

        $result = $calculator->calculate();

        $informationCollection = $calculator->getScoreInformationCollection();
        $this->assertCount(4, $informationCollection);
        $this->assertEquals(6, $result);
        $this->assertTrue($calculator->hasPenalties());
    }

    public function testCalculatorWithSoftPenaltyButNoPenaltiesShouldApply() {
        $calculationsCollection = new CalculationsCollection();

        $calculationsCollection
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation',
                    'timeframe',
                    10,
                    100,
                    new Penalty(20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY)
                ), 10, 100, false)
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation 2',
                    'timeframe',
                    10,
                    100
                ), 100, 100, false)
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation 3',
                    'timeframe',
                    10,
                    100
                ), 10, 10, false) // Only 10 records, 10 out of 10 = 10 points
            ->addCalculation(new Calculation\PercentageCalculation(
                'Test Calculation',
                'timeframe',
                10,
                100,
                new Penalty(20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY)
            ), 10, 100, false);

        /** @var Calculator $calculator */
        $calculator = $this->getMockForAbstractClass('Pawshake\SellerScore\Calculator');

        // Set the calculation collection.
        $reflection = new ReflectionClass($calculator);
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);

        $reflection_property->setValue($calculator, $calculationsCollection);

        $result = $calculator->calculate();

        $informationCollection = $calculator->getScoreInformationCollection();
        $this->assertCount(4, $informationCollection);
        $this->assertEquals(22, $result);
        $this->assertFalse($calculator->hasPenalties());
    }

    public function testCalculatorWithDisabledPenalties() {
        $calculationsCollection = new CalculationsCollection();

        $calculationsCollection
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation',
                    'timeframe',
                    10,
                    100,
                    new Penalty(20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY)
                ), 10, 100)
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
                ), 10, 1) // Only 10 records, 10 out of 10 = 10 points
            ->addCalculation(new Calculation\PercentageCalculation(
                'Test Calculation',
                'timeframe',
                10,
                100,
                new Penalty(20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY)
            ), 10, 100);

        /** @var Calculator $calculator */
        $calculator = $this->getMockForAbstractClass('Pawshake\SellerScore\Calculator');

        $reflection = new ReflectionClass($calculator);

        // Set the calculation collection.
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($calculator, $calculationsCollection);

        // Disable penalties.
        $calculator->disablePenalties();

        $result = $calculator->calculate();

        $informationCollection = $calculator->getScoreInformationCollection();
        $this->assertCount(4, $informationCollection);
        $this->assertEquals(22, $result);
        $this->assertFalse($calculator->hasPenalties());
    }

    public function testCalculatorWithHardPenalty() {
        $calculationsCollection = new CalculationsCollection();

        $calculationsCollection
            ->addCalculation(
                new Calculation\PercentageCalculation(
                    'Test Calculation',
                    'timeframe',
                    10,
                    100,
                    null,
                    new HardPenalty(20, Penalty::COMPARISON_SMALLER)
                ), 10, 100)
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
                ), 10, 10) // Only 10 records, 10 out of 10 = 10 points
            ->addCalculation(new Calculation\PercentageCalculation(
                'Test Calculation',
                'timeframe',
                10,
                100,
                new Penalty(20, Penalty::COMPARISON_SMALLER, 0.5, Penalty::OPERATION_MULTIPLY)
            ), 10, 100);

        /** @var Calculator $calculator */
        $calculator = $this->getMockForAbstractClass('Pawshake\SellerScore\Calculator');

        // Set the calculation collection.
        $reflection = new ReflectionClass($calculator);
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);

        $reflection_property->setValue($calculator, $calculationsCollection);

        $result = $calculator->calculate();

        $informationCollection = $calculator->getScoreInformationCollection();

        $this->assertCount(1, $informationCollection);
        $this->assertEquals(-10000, $result);
        $this->assertTrue($calculator->hasPenalties());
    }

    public function testCalculatorWithDynamicRemoveOfCalculation() {
        $calculationCollection = new CalculationsCollection();

        $calculationCollection
            ->addCalculationConfiguration('unique_calculation_one', new Calculation\PercentageCalculation('Lifetime Conversion Rate for unique users', 'last year', 50))
            ->addCalculationConfiguration('unique_calculation_two', new Calculation\PercentageCalculation('Conversion Rate', 'last 10 unique users', 50));

        /** @var Calculator $calculator */
        $calculator = $this->getMockForAbstractClass('Pawshake\SellerScore\Calculator');

        // Set the calculation collection.
        $reflection = new ReflectionClass($calculator);
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($calculator, $calculationCollection);

        $reflection = new ReflectionClass($calculator);
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);
        $value = $reflection_property->getValue($calculator);
        $this->assertCount(2, $value);

        $calculator->removeCalculation('unique_calculation_one');

        $reflection = new ReflectionClass($calculator);
        $reflection_property = $reflection->getProperty('calculationCollection');
        $reflection_property->setAccessible(true);
        $value = $reflection_property->getValue($calculator);
        $this->assertCount(1, $value);
    }
}
