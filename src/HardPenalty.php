<?php

namespace Pawshake\SellerScore;

/**
 * Class HardPenalty
 * @package Pawshake\SellerScore
 */
class HardPenalty extends Penalty {

    /**
     * HardPenalty constructor.
     *
     * @param $threshold
     * @param $comparison
     */
    public function __construct($threshold, $comparison)
    {
        parent::__construct($threshold, $comparison, -10000, self::OPERATION_FIXED_VALUE);
    }
}