<?php

namespace Pawshake\SellerScore;

class PenaltyResult
{
    /**
     * @var int
     */
    private $points;

    /**
     * @var string
     */
    private $description;

    /**
     * @param int $points
     * @param string $description
     */
    public function __construct($points, $description) {
        $this->points = $points;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}
