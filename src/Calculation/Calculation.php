<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\CalculationResult;
use Pawshake\SellerScore\HardPenalty;
use Pawshake\SellerScore\Penalty;
use Pawshake\SellerScore\ScoreInformation;

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
     * @var null|Penalty
     */
    private $softPenalty;

    /**
     * @var null|HardPenalty
     */
    private $hardPenalty;

    /**
     * @param string $name
     * @param string $timeframe
     * @param int $points Maximum mount of points this calculation is worth.
     * @param Penalty|null $softPenalty
     * @param HardPenalty|null $hardPenalty
     */
    public function __construct(
        $name,
        $timeframe,
        $points,
        Penalty $softPenalty = null,
        HardPenalty $hardPenalty = null
    ) {
        $this->name = $name;
        $this->timeframe = $timeframe;
        $this->points = $points;
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
        return $this->getName() . ' for ' . $this->getTimeFrame();
    }

    /**
     * @param int $input
     * @param int $total
     *
     * @return CalculationResult
     * @throws \InvalidArgumentException Default implementation assumes input is an integer.
     */
    public function calculate($input, $total = null)
    {
        $rawInput = $input;

        if (!is_numeric($input)) {
            throw new \InvalidArgumentException('Calculation input should be an integer');
        }

        // Converts the input into readable input (percentage for example).
        $input = $this->convertInput($input, $total);
        $penaltyInput = $this->comparePenaltiesWithConvertedInput() ? $input : $rawInput;

        $addHardPenalty = ($this->hardPenalty instanceof HardPenalty && $this->hardPenalty->applies($penaltyInput));
        $addSoftPenalty = ($this->softPenalty instanceof Penalty && $this->softPenalty->applies($penaltyInput));

        $pointsEarned = $this->calculatePoints($input);

        $scoreInformation = new ScoreInformation(
            $this->getDescription(),
            $rawInput,
            $input,
            $this->points,
            $pointsEarned,
            $addSoftPenalty ? $this->softPenalty->getDescription($penaltyInput) : null,
            $addHardPenalty ? $this->hardPenalty->getDescription($penaltyInput) : null
        );

        return new CalculationResult(
            $pointsEarned,
            $scoreInformation,
            $addSoftPenalty ? $this->softPenalty : null,
            $addHardPenalty ? $this->hardPenalty : null
        );
    }

    /**
     * @return bool
     */
    abstract protected function comparePenaltiesWithConvertedInput();

    abstract protected function convertInput($input, $total = null);

    /**
     * @param int $input
     *
     * @return int
     */
    abstract protected function calculatePoints($input);
}
