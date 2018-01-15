<?php

namespace Pawshake\SellerScore;

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

            $calculationResult = $calculation->calculate($input);
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
