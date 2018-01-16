<?php

namespace Pawshake\SellerScore;

use Pawshake\SellerScore\Calculation\Calculation;

/**
 * This class will calculate the seller score for a host, based
 * on the parameters provided.
 */
abstract class Calculator
{
    /**
     * @var ScoreInformationCollection
     */
    private $scoreInformationCollection;

    /**
     * @var CalculationsCollection
     */
    protected $calculationCollection;

    /**
     * Configure the calculations collection
     */
    abstract protected function configure();

    /**
     * @param int $startingPoints
     * @return int
     */
    public function calculate($startingPoints = 0) {
        if (null === $this->calculationCollection) {
            $this->configure();
        }
        return $this->calculateCollection($this->calculationCollection, $startingPoints);
    }

    /**
     * @param string $id
     * @param int $input
     * @param int|null $total
     */
    public function addCalculationInput($id, $input, $total = null) {
        if (null === $this->calculationCollection) {
            $this->configure();
        }
        $this->calculationCollection->addCalculationInput($id, $input, $total);
    }

    /**
     * @return ScoreInformationCollection
     */
    public function getScoreInformationCollection()
    {
        return $this->scoreInformationCollection;
    }

    /**
     * @param CalculationsCollection $calculationsCollection
     * @param int $currentPoints
     *
     * @return int
     * @throws \InvalidArgumentException Calculation configuration is not complete.
     */
    private function calculateCollection(CalculationsCollection $calculationsCollection, $currentPoints = 0) {
        $this->scoreInformationCollection = new ScoreInformationCollection();
        foreach ($calculationsCollection as $calculationItem) {
            /** @var Calculation $calculation */
            $calculation = $calculationItem[CalculationsCollection::FIELD_CALCULATION];
            $input = $calculationItem[CalculationsCollection::FIELD_INPUT];
            $total = $calculationItem[CalculationsCollection::FIELD_TOTAL];

            if (!$calculation instanceof Calculation || $input === null) {
                throw new \InvalidArgumentException('Calculation configuration is not complete.');
            }

            $calculationResult = $calculation->calculate($input, $total);
            $currentPoints += $calculationResult->getPoints();

            $this->scoreInformationCollection->addScoreInformation($calculationResult->getScoreInformation());
        }

        return $currentPoints;
    }
}
