<?php

namespace Pawshake\SellerScore;

use Pawshake\SellerScore\Calculations\Calculation;

/**
 * This class will calculate the seller score for a host, based
 * on the parameters provided.
 */
class Calculator
{
    /**
     * @param CalculationsCollection $calculationsCollection
     * @param int $currentPoints
     *
     * @return int
     */
    public function calculateCollection(CalculationsCollection $calculationsCollection, $currentPoints = 0) {
        foreach ($calculationsCollection as $calculationItem) {
            /** @var Calculation $calculation */
            $calculation = $calculationItem['calculation'];
            $input = $calculationItem['input'];

            $calculationResult = $calculation->calculate($input);
            $currentPoints += $calculationResult->getPoints();
        }

        return $currentPoints;
    }
}
