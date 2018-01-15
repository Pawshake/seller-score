<?php

namespace Pawshake\SellerScore;

class ScoreInformation
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $input;

    /**
     * @var int
     */
    private $pointsEarned;

    /**
     * @var string
     */
    private $penalty;

    /**
     * @var int
     */
    private $points;

    /**
     * @param string $description
     * @param string $input
     * @param int $points
     * @param int $pointsEarned
     * @param string|null $penalty
     */
    public function __construct($description, $input, $points, $pointsEarned, $penalty = null) {
        $this->description = $description;
        $this->input = $input;
        $this->points = $points;
        $this->pointsEarned = $pointsEarned;
        $this->penalty = $penalty;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return int
     */
    public function getPointsEarned()
    {
        return $this->pointsEarned;
    }

    /**
     * @return string
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }
}
