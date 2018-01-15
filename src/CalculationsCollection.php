<?php

namespace Pawshake\SellerScore;

use ArrayIterator;
use IteratorAggregate;
use Pawshake\SellerScore\Calculation\Calculation;
use Traversable;

class CalculationsCollection implements IteratorAggregate
{
    private $calculations = [];

    /**
     * @param string|int $id
     * @param Calculation $calculation
     *
     * @return CalculationsCollection
     */
    public function addCalculationConfiguration($id, Calculation $calculation)
    {
        if (isset($this->calculations[$id]) && is_array($this->calculations[$id])) {
            $this->calculations[$id] = array_merge($this->calculations[$id], [
                'calculation' => $calculation,
            ]);
        } else {
            $this->calculations[$id] = [
                'calculation' => $calculation,
                'input' => null,
                'maximum_total' => null,
            ];
        }

        return $this;
    }

    /**
     * @param string|int $id
     * @param int $input
     * @param int|null $maximumInput
     *
     * @return CalculationsCollection
     */
    public function addCalculationInput($id, $input, $maximumInput = null)
    {
        if (isset($this->calculations[$id]) && is_array($this->calculations[$id])) {
            $this->calculations[$id] = array_merge($this->calculations[$id], [
                'input' => $input,
                'maximum_total' => $maximumInput,
            ]);
        } else {
            $this->calculations[$id] = [
                'calculation' => null,
                'input' => $input,
                'maximum_total' => $maximumInput,
            ];
        }

        return $this;
    }

    /**
     * @param Calculation $calculation
     * @param int $input
     * @param int $maximumTotal
     *
     * @return CalculationsCollection
     */
    public function addCalculation(Calculation $calculation, $input, $maximumTotal = null)
    {
        $this->calculations[] = [
            'calculation' => $calculation,
            'input' => $input,
            'maximum_total' => $maximumTotal,
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
