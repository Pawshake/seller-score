<?php

namespace Pawshake\SellerScore;

class Penalty
{
    CONST OPERATION_PLUS = '+';
    CONST OPERATION_MULTIPLY = '*';

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $penalty;

    /**
     * @var string
     */
    private $penaltyOperation;

    /**
     * @param int $amount
     * @param int $penalty
     * @param string $penaltyOperation
     */
    public function __construct($amount, $penalty, $penaltyOperation)
    {
        $this->amount = $amount;
        $this->penalty = $penalty;
        $this->penaltyOperation = $penaltyOperation;
    }

}
