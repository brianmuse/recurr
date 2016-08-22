<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

/**
 * An iCal RRULE property.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.8.5.3 RFC 5545 RRULE Definition
 */
final class RRule extends AbstractProperty
{
    /**
     * @type string
     */
    const NAME = 'RRULE';

    /**
     * @inheritDoc
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
        // There are specific params for RRULE that need to be validated. All optional params are valid, tho ignored.
        return true;
    }
}
