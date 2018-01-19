<?php

namespace Pawshake\SellerScore;

class ScoreInformation
{
    /**
     * @var string
     */
    private $description;

    private $rawInput;

    /**
     * @var string
     */
    private $input;

    /**
     * @var int
     */
    private $pointsEarned;


    /** @var string $softPenalty */
    private $softPenalty;

    /** @var string $hardPenalty */
    private $hardPenalty;

    /**
     * @var int
     */
    private $points;

    /**
     * ScoreInformation constructor.
     * @param $description
     * @param $rawInput
     * @param $input
     * @param $points
     * @param $pointsEarned
     * @param string $softPenalty
     * @param string $hardPenalty
     */
    public function __construct($description, $rawInput, $input, $points, $pointsEarned, $softPenalty = '', $hardPenalty = '') {
        $this->description = $description;
        $this->rawInput = $rawInput;
        $this->input = $input;
        $this->points = $points;
        $this->pointsEarned = $pointsEarned;
        $this->softPenalty = $softPenalty;
        $this->hardPenalty = $hardPenalty;
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
    public function getSoftPenalty()
    {
        return $this->softPenalty;
    }

    /**
     * @return string
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
}
