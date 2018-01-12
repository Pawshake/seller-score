<?php

namespace Pawshake\SellerScore;

class ScoreInformation implements \JsonSerializable
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var int
     */
    private $score;

    /**
     * @var string
     */
    private $penalty;

    /**
     * @param string $description
     * @param string $amount
     * @param int $score
     * @param string|null $penalty
     */
    public function __construct($description, $amount, $score, $penalty = null) {
        $this->description = $description;
        $this->amount = $amount;
        $this->score = $score;
        $this->penalty = $penalty;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() {
        return [
            'text' => $this->description,
            'amount' => $this->amount,
            'score' => $this->score,
        ];
    }
}
