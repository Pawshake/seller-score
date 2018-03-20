<?php

namespace Pawshake\SellerScore\Calculation;

use Pawshake\SellerScore\CalculationResult;
use Pawshake\SellerScore\HardPenalty;
use Pawshake\SellerScore\Penalty;
use Pawshake\SellerScore\ScoreInformation;

abstract class Calculation
{
    const RANGE = 'Range';
    const COUNTDOWN = 'Countdown';
    const PERCENTAGE = 'Percentage';
    const RANDOM_BOOST = 'Random boost';
    const BOOLEAN = 'Boolean';

    private $type;

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
        $this->type = $this->setType();
        $this->name = $name;
        $this->timeframe = $timeframe;
        $this->points = $points;
        $this->softPenalty = $softPenalty;
        $this->hardPenalty = $hardPenalty;
    }

    /**
     * @return string
     */
    private function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    private function getTimeFrame()
    {
        return $this->timeframe;
    }

    /**
     * @return string
     */
    protected function getDescription()
    {
        return $this->getType() . ': ' . $this->getName() . ' for ' . $this->getTimeFrame();
    }

    /**
     * @param int $input
     * @param int $total
     * @param boolean $applyPenalties
     *
     * @return CalculationResult
     * @throws \InvalidArgumentException Default implementation assumes input is an integer.
     */
    public function calculate($input, $total = null, $applyPenalties = true)
    {
        $rawInput = $input;

        if (!is_numeric($input)) {
            throw new \InvalidArgumentException('Calculation input should be an integer');
        }

        // Converts the input into readable input (percentage for example).
        $input = $this->convertInput($input, $total);
        $penaltyInput = $this->comparePenaltiesWithConvertedInput() ? $input : $rawInput;

        if ($applyPenalties) {
            $addHardPenalty = ($this->hardPenalty instanceof HardPenalty && $this->hardPenalty->applies($penaltyInput));
            $addSoftPenalty = ($this->softPenalty instanceof Penalty && $this->softPenalty->applies($penaltyInput));
        } else {
            $addSoftPenalty = $addHardPenalty = false;
        }

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

    abstract protected function setType();

    /**
     * @param int $input
     *
     * @return int
     */
    abstract protected function calculatePoints($input);
}
