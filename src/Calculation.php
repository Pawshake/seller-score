<?php

namespace Pawshake\SellerScore;

use Pawshake\SellerScore\CalculationMethod;
use Pawshake\SellerScore\CalculationResult;
use Pawshake\SellerScore\CountdownMethod;
use Pawshake\SellerScore\Penalty;
use Pawshake\SellerScore\PercentageMethod;
use Pawshake\SellerScore\RangeMethod;
use Pawshake\SellerScore\ScoreInformation;

final class Calculation
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
    private $points;

    /**
     * @var CalculationMethod|RangeMethod|PercentageMethod|CountdownMethod
     */
    private $calculationMethod;

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

    /**
     * @param int $input
     *
     * @return CalculationResult
     * @throws \InvalidArgumentException Default implementation assumes input is an integer.
     */
    public function calculate($input)
    {
        if (!is_numeric($input)) {
            throw new \InvalidArgumentException('Calculation input should be an integer');
        }

        $description = $this->getName()
            . ' for ' . $this->getTimeFrame()
            . ' ' . $this->calculationMethod->getType();
        $pointsEarned = 0;

        switch ($this->calculationMethod->getType()) {
            case CalculationMethod::TYPE_RANGE:
                $description .= ' ' . $this->calculationMethod->getRangeDescription();
                $range = $this->calculationMethod->getTo() - $this->calculationMethod->getFrom();
                $correctedStartValue = $input - $this->calculationMethod->getFrom();
                $percentage = ($correctedStartValue * 100) / $range;

                $pointsEarned = (int) round($percentage * ($this->points / 100));
                break;

            case CalculationMethod::TYPE_COUNTDOWN:
                $pointsToAdd = $this->calculationMethod->getStart() - ($input * $this->calculationMethod->getIterate());
                if ($pointsToAdd >= 0) { // Don't add less than 0.
                    $pointsEarned = (int)round($this->points + $pointsToAdd);
                }
                break;

            case CalculationMethod::TYPE_PERCENTAGE:
            default:
                $percentage = ($input / $this->calculationMethod->getTotal()) * 100;
                $pointsEarned = (int) round($percentage * ($this->points / 100));
                break;
        }

        // Calculate penalties
        $penalty = null;
        if (isset($this->hardPenalty) && $this->hardPenalty->matches($input)) {
            $pointsEarned = $this->hardPenalty->calculate($input, $pointsEarned);
            $penalty = 'Hard Penalty: ' . $this->hardPenalty->getDescription($input);
        } elseif (isset($this->softPenalty) && $this->softPenalty->matches($input)) {
            $pointsEarned = $this->softPenalty->calculate($input, $pointsEarned);
            $penalty = 'Soft Penalty: ' . $this->softPenalty->getDescription($input);
        }

        $scoreInformation = new ScoreInformation($description, $input, $this->points, $pointsEarned, $penalty);

        return new CalculationResult($pointsEarned, $scoreInformation);
    }
}
