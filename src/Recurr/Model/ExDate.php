<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

/**
 * An iCal EXDATE property.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.8.5.1 RFC 5545 EXDATE Definition
 */
final class ExDate extends AbstractProperty
{
    /**
     * @type string
     */
    const NAME = 'EXDATE';

    /**
     * @type string[]
     */
    private static $valid_value_types = [
        'DATE-TIME',
        'DATE',
    ];

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function validateParameterList(array $parameter_list)
    {
        foreach ($parameter_list as $parameter) {
            $value = $parameter->getValue();
            if ($parameter->getName() === Parameter::PARAM_VALUE && !in_array($value, static::$valid_value_types)) {
                return false;
            }
        }

        return true;
    }
}
