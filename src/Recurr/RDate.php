<?php

namespace Recurr;

/**
 */
class DateExclusion
{
    public $date;

    public $hasTime;

    /** @var bool */
    public $isUtcExplicit;

    /**
     * Constructor
     *
     * @param \DateTime $date
     * @param bool      $hasTime
     * @param bool      $isUtcExplicit
     */
    public function __construct(\DateTime $date, $hasTime = true, $isUtcExplicit = false)
    {
        $this->date          = $date;
        $this->hasTime       = $hasTime;
        $this->isUtcExplicit = $isUtcExplicit;
    }
}
