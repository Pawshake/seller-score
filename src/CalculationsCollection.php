<?php

namespace Pawshake\SellerScore;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class CalculationsCollection implements IteratorAggregate
{
    private $calculations = [];

    /**
     * @param Calculation $calculation
     * @param int $input
     *
     * @return CalculationsCollection
     */
    public function addCalculation(Calculation $calculation, $input) {
        $this->calculations[] = [
            'calculation' => $calculation,
            'input' => $input,
        ];

        return $this;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->calculations);
    }
}
