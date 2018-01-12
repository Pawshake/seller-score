<?php

namespace Pawshake\SellerScore\Calculations;

use Pawshake\SellerScore\CalculationMethod;
use Pawshake\SellerScore\CalculationResult;
use Pawshake\SellerScore\Penalty;
use Pawshake\SellerScore\PercentageMethod;
use Pawshake\SellerScore\RangeMethod;
use Pawshake\SellerScore\ScoreInformation;

abstract class Calculation
{
    /**
     * @var int
     */
    private $points;

    /**
     * @var CalculationMethod|RangeMethod|PercentageMethod
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
     * @param int $points Maximum mount of points this calculation is worth.
     * @param CalculationMethod $calculationMethod
     * @param Penalty|null $softPenalty
     * @param Penalty|null $hardPenalty
     *
     */
    public function __construct(
        $points,
        CalculationMethod $calculationMethod,
        Penalty $softPenalty = null,
        Penalty $hardPenalty = null
    ) {

        $this->points = $points;
        $this->calculationMethod = $calculationMethod;
        $this->softPenalty = $softPenalty;
        $this->hardPenalty = $hardPenalty;
    }

    /**
     * @return string
     */
    public function getTimeFrame() {
        return 'last year';
    }

    /**
     * @param mixed|int $input
     *
     * @return CalculationResult
     * @throws \InvalidArgumentException Default implementation assumes input is an integer.
     */
    public function calculate($input)
    {
        if (!is_numeric($input)) {
            throw new \InvalidArgumentException('Calculation input should be an integer');
        }

        $description = $this->getClassDescription()
            . ' for ' . $this->getTimeFrame()
            . ' ' . $this->calculationMethod->getType();
        $pointsEarned = 0;

        switch ($this->calculationMethod->getType()) {
            case CalculationMethod::TYPE_RANGE:
                $description .= ' ' . $this->calculationMethod->getRangeDescription();
                $range = $this->calculationMethod->getTo() - $this->calculationMethod->getFrom();
                $correctedStartValue = $input - $this->calculationMethod->getFrom();
                $percentage = ($correctedStartValue * 100) / $range;

                $pointsEarned = round($percentage * ($this->points / 100));
                break;

            case CalculationMethod::TYPE_PERCENTAGE:
            default:
                $pointsEarned = ($input / $this->calculationMethod->getTotal()) * 100;
                break;
        }

        $scoreInformation = new ScoreInformation($description, $input, $this->points, $pointsEarned);

        return new CalculationResult($pointsEarned, $scoreInformation);
    }

    /**
     * Get the class description.
     * By default based on it's name.
     *
     * @return string
     */
    protected function getClassDescription()
    {
        $re = '/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x';
        $a = preg_split($re, get_class($this));

        return implode(' ', $a);
    }
}
