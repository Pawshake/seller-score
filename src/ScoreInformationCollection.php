<?php

namespace Pawshake\SellerScore;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class ScoreInformationCollection implements IteratorAggregate
{
    private $scoreInformationItems;

    public function addScoreInformationItems(ScoreInformation ...$scoreInformationItems) {
        $this->scoreInformationItems = $scoreInformationItems;
    }

    public function addScoreInformation(ScoreInformation $scoreInformation) {
        $this->scoreInformationItems[] = $scoreInformation;
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
        return new ArrayIterator($this->scoreInformationItems);
    }
}
