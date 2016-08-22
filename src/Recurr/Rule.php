<?php

namespace Recurr;
use Robin\Api\Libraries\Calendar\Model\ICal\AbstractProperty;

/**
 */
abstract class Rule extends AbstractProperty
{
    protected $recur;

    public function __construct($value, $parameters = [])
    {
        $this->recur = new Recur($value);

        parent::__construct($value, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function validateParameterList(array $parameters)
    {
        // There are specific params for rules that need to be validated.
        // All optional params are valid, though ignored.
        return true;
    }
}
