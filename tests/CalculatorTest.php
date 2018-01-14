<?php

namespace Pawshake\SellerScore;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check for syntax errors.
     */
    public function testIsThereAnySyntaxError()
    {
        $var = new Calculator;
        $this->assertTrue(is_object($var));
        unset($var);
    }
}
