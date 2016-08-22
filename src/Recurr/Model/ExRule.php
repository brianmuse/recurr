<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

/**
 * An iCal ExRule property.
 *
 * NOTE: This property is defined in RFC 2245, which has since been deprecated.
 *
 * @see https://tools.ietf.org/html/rfc2445#section-4.8.5.2 RFC 2245 RRULE Definition
 */
final class ExRule extends AbstractProperty
{
    /**
     * @type string
     */
    const NAME = 'EXRULE';

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
        // There are specific params for EXRULE that need to be validated. All optional params are valid, tho ignored.
        return true;
    }
}
