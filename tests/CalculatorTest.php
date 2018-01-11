<?php

class CalculatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Check for syntax errors.
     */
    public function testIsThereAnySyntaxError()
    {
        $var = new Pawshake\SellerScore\Calculator;
        $this->assertTrue(is_object($var));
        unset($var);
    }
}
