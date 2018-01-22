<?php

namespace Pawshake\SellerScore;

use ArrayIterator;
use IteratorAggregate;
use Pawshake\SellerScore\Calculation\Calculation;
use Traversable;

class CalculationsCollection implements IteratorAggregate
{
    const FIELD_CALCULATION = 'calculation';
    const FIELD_INPUT = 'input';
    const FIELD_TOTAL = 'total';
    const FIELD_APPLY_PENALTIES = 'apply_penalties';

    /**
     * @var array
     */
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
                static::FIELD_CALCULATION => $calculation,
            ]);
        } else {
            $this->calculations[$id] = [
                static::FIELD_CALCULATION => $calculation,
                static::FIELD_INPUT => null,
                static::FIELD_TOTAL => null,
                static::FIELD_APPLY_PENALTIES => true,
            ];
        }

        return $this;
    }

    /**
     * @param string|int $id
     * @param int $input
     * @param int|null $total
     * @param boolean $applyPenalties
     *
     * @return CalculationsCollection
     */
    public function addCalculationInput($id, $input, $total = null, $applyPenalties = true)
    {
        if (isset($this->calculations[$id]) && is_array($this->calculations[$id])) {
            $this->calculations[$id] = array_merge($this->calculations[$id], [
                static::FIELD_INPUT => $input,
                static::FIELD_TOTAL => $total,
                static::FIELD_APPLY_PENALTIES => $applyPenalties,
            ]);
        } else {
            $this->calculations[$id] = [
                static::FIELD_CALCULATION => null,
                static::FIELD_INPUT => $input,
                static::FIELD_TOTAL => $total,
                static::FIELD_APPLY_PENALTIES => $applyPenalties,
            ];
        }

        return $this;
    }

    /**
     * @param Calculation $calculation
     * @param int $input
     * @param int $total
     * @param boolean $applyPenalties
     *
     * @return CalculationsCollection
     */
    public function addCalculation(Calculation $calculation, $input, $total = null, $applyPenalties = true)
    {
        $this->calculations[] = [
            static::FIELD_CALCULATION => $calculation,
            static::FIELD_INPUT => $input,
            static::FIELD_TOTAL => $total,
            static::FIELD_APPLY_PENALTIES => $applyPenalties
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
