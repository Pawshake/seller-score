<?php

namespace Pawshake\SellerScore;

use Pawshake\SellerScore\Calculations\Calculation;

/**
 * This class will calculate the seller score for a host, based
 * on the parameters provided.
 */
class Calculator
{
    public function calculateCollection(CalculationsCollection $calculationsCollection) {
        $earnedPoints = 0;
        foreach ($calculationsCollection as $calculation) {
            /** @var Calculation $calculation */
            $earnedPoints = $calculation->calculate(0);
        }

        return $earnedPoints;
    }
}
