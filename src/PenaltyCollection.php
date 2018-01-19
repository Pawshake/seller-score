<?php

namespace Pawshake\SellerScore;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class PenaltyCollection implements IteratorAggregate
{
    private $penalties = [];

    public function add(Penalty $penalty) {
        $this->penalties[] = $penalty;
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
        return new ArrayIterator($this->penalties);
    }
}