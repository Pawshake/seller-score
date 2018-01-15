<?php

namespace Pawshake\SellerScore;

abstract class Calculation
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $timeframe;

    /**
     * @var int
     */
    protected $points;

    /**
     * @var CalculationMethod|RangeMethod|PercentageMethod|CountdownMethod
     */
    protected $calculationMethod;

    /**
     * @var null|Penalty
     */
    private $softPenalty;

    /**
     * @var null|Penalty
     */
    private $hardPenalty;


    /**
     * @param string $name
     * @param string $timeframe
     * @param int $points Maximum mount of points this calculation is worth.
     * @param CalculationMethod $calculationMethod
     * @param Penalty|null $softPenalty
     * @param Penalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        CalculationMethod $calculationMethod,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {
        $this->name = $name;
        $this->timeframe = $timeframe;
        $this->points = $points;
        $this->calculationMethod = $calculationMethod;
        $this->softPenalty = $softPenalty;
        $this->hardPenalty = $hardPenalty;
    }

    private function getName() {
        return $this->name;
    }
    /**
     * @return string
     */
    private function getTimeFrame() {
        return $this->timeframe;
    }

    private function getDescription() {

        $description = $this->getName()
            . ' for ' . $this->getTimeFrame()
            . ' ' . $this->calculationMethod->getType();

        if (CalculationMethod::TYPE_RANGE === $this->calculationMethod->getType()) {
            $description .= ' ' . $this->calculationMethod->getRangeDescription();
        }

        return $description;
    }

    /**
     * @param int $input
     * @param int $maximumTotal
     *
     * @return CalculationResult
     * @throws \InvalidArgumentException Default implementation assumes input is an integer.
     */
    public function calculate($input, $maximumTotal = null)
    {
        if (!is_numeric($input)) {
            throw new \InvalidArgumentException('Calculation input should be an integer');
        }

        $penaltyInput = $input;

        $pointsEarned = $this->calculatePoints($input, $maximumTotal);

        // Calculate penalties
        $penaltyResult = $this->calculatePenalty($penaltyInput, $pointsEarned);
        $pointsEarned = $penaltyResult->getPoints();

        $scoreInformation = new ScoreInformation($this->getDescription(), $input, $this->points, $pointsEarned, $penaltyResult->getDescription());

        return new CalculationResult($pointsEarned, $scoreInformation);
    }

    /**
     * @param int $input
     * @param int|null $maximumTotal
     * @return int
     */
    abstract protected function calculatePoints($input, $maximumTotal = null);

    /**
     * @param int $input
     * @param int $pointsEarned
     * @return PenaltyResult
     */
    protected function calculatePenalty($input, $pointsEarned)
    {
        if (isset($this->hardPenalty) && $this->hardPenalty->matches($input)) {
            return new PenaltyResult(
                $this->hardPenalty->calculate($input, $pointsEarned),
                'Hard Penalty: ' . $this->hardPenalty->getDescription($input)
            );
        } elseif (isset($this->softPenalty) && $this->softPenalty->matches($input)) {
            return new PenaltyResult(
                $this->softPenalty->calculate($input, $pointsEarned),
                'Soft Penalty: ' . $this->softPenalty->getDescription($input)
            );
        }

        return new PenaltyResult($pointsEarned, 'No Penalty');
    }
}
