<?php

namespace Pawshake\SellerScore;

class CalculationResult
{
    /**
     * @var bool
     */
    private $soft_penalty;

    /**
     * @var bool
     */
    private $hard_penalty;

    /**
     * @var int
     */
    private $points;

    /**
     * @var ScoreInformation
     */
    private $scoreInformation;

    /**
     * @param int $points
     * @param ScoreInformation $scoreInformation
     * @param bool $soft_penalty
     * @param bool $hard_penalty
     */
    public function __construct(
        $points,
        ScoreInformation $scoreInformation,
        $soft_penalty = false,
        $hard_penalty = false
    ) {
        $this->soft_penalty = $soft_penalty;
        $this->hard_penalty = $hard_penalty;
        $this->points = $points;
        $this->scoreInformation = $scoreInformation;
    }

    /**
     * @return bool
     */
    public function hasSoftPenalty()
    {
        return $this->soft_penalty;
    }

    /**
     * @return bool
     */
    public function hasHardPenalty()
    {
        return $this->hard_penalty;
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
