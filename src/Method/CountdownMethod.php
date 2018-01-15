<?php

namespace Pawshake\SellerScore\Method;

class CountdownMethod implements CalculationMethod
{
    /**
     * @var int
     */
    private $start;

    /**
     * @var int
     */
    private $iterate;

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE_COUNTDOWN;
    }

    /**
     * @param int $start
     * @param int $iterate
     */
    public function __construct($start, $iterate)
    {
        $this->start = $start;
        $this->iterate = $iterate;
    }

    /**
     * @return string
     */
    public function getCountdownDescription()
    {
        return 'start: ' . $this->getStart() . ' - ' . $this->getIterate() . '/count';
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getIterate()
    {
        return $this->iterate;
    }
}
