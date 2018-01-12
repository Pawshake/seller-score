<?php

namespace Pawshake\SellerScore;

class CalculationTest extends \PHPUnit_Framework_TestCase
{
    public function testPercentageMethod()
    {
        $calculation = new Calculations\LastCalendarUpdate(
            100, new PercentageMethod(100)
        );

        $result = $calculation->calculate(10);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals(10, $result->getPoints());
    }

    public function testRangeMethod()
    {
        $calculation = new Calculations\LastCalendarUpdate(
            100, new RangeMethod(10, 100)
        );

        $result = $calculation->calculate(20);

        $this->assertInstanceOf(CalculationResult::class, $result);
        $this->assertEquals(11, $result->getPoints());
    }
}
