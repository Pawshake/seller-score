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
     * @var bool
     */
    private $is_penalty;

    /**
     * @param string $text
     * @param string $amount
     * @param int $score
     */
    public function __construct($description, $amount, $score, $is_penalty) {
        $this->description = $description;
        $this->amount = $amount;
        $this->score = $score;
        $this->is_penalty = $is_penalty;
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
