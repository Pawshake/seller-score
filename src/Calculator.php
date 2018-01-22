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
     * @var PenaltyCollection
     */
    private $penaltyCollection;

    /**
     * @var CalculationsCollection
     */
    protected $calculationCollection;

    /**
     * Configure the calculations collection
     */
    abstract protected function configure();

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function calculate() {
        if (null === $this->calculationCollection) {
            $this->configure();
        }
        return $this->calculateCollection($this->calculationCollection);
    }

    /**
     * @param string $id
     * @param int $input
     * @param int|null $total
     * @param boolean $applyPenalties
     */
    public function addCalculationInput($id, $input, $total = null, $applyPenalties = true) {
        if (null === $this->calculationCollection) {
            $this->configure();
        }
        $this->calculationCollection->addCalculationInput($id, $input, $total, $applyPenalties);
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
     *
     * @return int
     * @throws \InvalidArgumentException Calculation configuration is not complete.
     */
    private function calculateCollection(CalculationsCollection $calculationsCollection) {
        $this->scoreInformationCollection = new ScoreInformationCollection();
        $this->penaltyCollection = new PenaltyCollection();
        $currentPoints = 0;

        foreach ($calculationsCollection as $calculationItem) {
            /** @var Calculation $calculation */
            $calculation = $calculationItem[CalculationsCollection::FIELD_CALCULATION];
            $input = $calculationItem[CalculationsCollection::FIELD_INPUT];
            $total = $calculationItem[CalculationsCollection::FIELD_TOTAL];
            $applyPenalties = $calculationItem[CalculationsCollection::FIELD_APPLY_PENALTIES];

            if (!$calculation instanceof Calculation || $input === null) {
                throw new \InvalidArgumentException('Calculation configuration is not complete.');
            }

            $calculationResult = $calculation->calculate($input, $total, $applyPenalties);
            $this->scoreInformationCollection->addScoreInformation($calculationResult->getScoreInformation());

            if ($calculationResult->hasHardPenalty()) {
                $penalty = $calculationResult->getHardPenalty();
                $currentPoints = $penalty->calculatePenalty($calculationResult->getPoints());
                // Reset the penalty collection - no need for this.
                $this->penaltyCollection = new PenaltyCollection();
                break;
            }

            if ($calculationResult->hasSoftPenalty()) {
                $this->penaltyCollection->add($calculationResult->getSoftPenalty());
            }

            $currentPoints += $calculationResult->getPoints();
        }

        /** @var Penalty $penalty */
        foreach ($this->penaltyCollection as $penalty) {
            $currentPoints = $penalty->calculatePenalty($currentPoints);
        }

        return $currentPoints;
    }
}
