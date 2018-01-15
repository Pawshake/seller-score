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
     * @throws \InvalidArgumentException Calculation configuration is not complete.
     */
    public function calculateCollection(CalculationsCollection $calculationsCollection, $currentPoints = 0) {
        $this->scoreInformationCollection = new ScoreInformationCollection();
        foreach ($calculationsCollection as $calculationItem) {
            /** @var Calculation $calculation */
            $calculation = $calculationItem[CalculationsCollection::FIELD_CALCULATION];
            $input = $calculationItem[CalculationsCollection::FIELD_INPUT];
            $maximumTotal = $calculationItem[CalculationsCollection::FIELD_MAXIMUM_TOTAL];

            if (!$calculation instanceof Calculation || $input === null) {
                throw new \InvalidArgumentException('Calculation configuration is not complete.');
            }

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
