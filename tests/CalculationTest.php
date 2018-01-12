<?php

namespace Pawshake\SellerScore;

class CalculationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider percentageProvider
     */
    public function testPercentageMethod($points, $total, $input, $expectedEarnedPoints)
    {
        $calculation = new Calculations\LastCalendarUpdate(
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
        $calculation = new Calculations\LastCalendarUpdate(
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
}
