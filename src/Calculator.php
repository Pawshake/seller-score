<?php

namespace Pawshake\SellerScore;

use Pawshake\SellerScore\Calculation\Calculation;

/**
 * This class will calculate the seller score for a host, based
 * on the parameters provided.
 */
class Calculator
{
    /**
     * @var ScoreInformationCollection
     */
    private $scoreInformationCollection;

    /**
     * @param CalculationsCollection $calculationsCollection
     * @param int $currentPoints
     *
     * @return int
     */
    public function calculateCollection(CalculationsCollection $calculationsCollection, $currentPoints = 0) {
        $this->scoreInformationCollection = new ScoreInformationCollection();
        foreach ($calculationsCollection as $calculationItem) {
            /** @var Calculation $calculation */
            $calculation = $calculationItem['calculation'];
            $input = $calculationItem['input'];
            $maximumTotal = $calculationItem['maximum_total'];

            $calculationResult = $calculation->calculate($input, $maximumTotal);
            $currentPoints += $calculationResult->getPoints();

            $this->scoreInformationCollection->addScoreInformation($calculationResult->getScoreInformation());
        }

        return $currentPoints;
    }

    /**
     * @return ScoreInformationCollection
     */
    public function getScoreInformationCollection()
    {
        return $this->scoreInformationCollection;
    }
}
