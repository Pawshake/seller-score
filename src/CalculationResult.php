<?php

namespace Pawshake\SellerScore;

/**
 * Class CalculationResult
 * @package Pawshake\SellerScore
 */
class CalculationResult
{
    /**
     * @var Penalty
     */
    private $softPenalty;

    /**
     * @var HardPenalty
     */
    private $hardPenalty;

    /**
     * @var int
     */
    private $points;

    /**
     * @var ScoreInformation
     */
    private $scoreInformation;

    /**
     * CalculationResult constructor.
     * @param $points
     * @param ScoreInformation $scoreInformation
     * @param Penalty|null $softPenalty
     * @param HardPenalty|null $hardPenalty
     */
    public function __construct(
        $points,
        ScoreInformation $scoreInformation,
        Penalty $softPenalty = null,
        HardPenalty $hardPenalty = null
    ) {
        $this->softPenalty = $softPenalty;
        $this->hardPenalty = $hardPenalty;
        $this->points = $points;
        $this->scoreInformation = $scoreInformation;
    }

    /**
     * @return bool
     */
    public function hasSoftPenalty()
    {
        return $this->softPenalty !== null && $this->softPenalty instanceof Penalty;
    }

    /**
     * @return bool
     */
    public function hasHardPenalty()
    {
        return $this->hardPenalty !== null && $this->hardPenalty instanceof HardPenalty;
    }

    /**
     * @return Penalty
     */
    public function getSoftPenalty()
    {
        return $this->softPenalty;
    }

    /**
     * @return Penalty
     */
    public function getHardPenalty()
    {
        return $this->hardPenalty;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return ScoreInformation
     */
    public function getScoreInformation()
    {
        return $this->scoreInformation;
    }
}
