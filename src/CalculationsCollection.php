<?php

namespace Pawshake\SellerScore;

use ArrayIterator;
use IteratorAggregate;
use Pawshake\SellerScore\Calculations\Calculation;
use Traversable;

class CalculationsCollection implements IteratorAggregate
{
    private $calculations;

    public function __construct(Calculation ...$calculations) {
        $this->calculations = $calculations;
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
