<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

use DateTimeZone;
use Recurr\Model\Type\Text;

/**
 * The identifier for the time zone definition for a time component in the property value.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.2.19
 */
final class TZId extends AbstractParameter
{
    const NAME = 'TZID';

    private $wrapped_php_timezone;

    /**
     * {@inheritdoc}
     */
    public function __construct(Text $value)
    {
        parent::__construct(self::NAME, $value);

        $this->wrapped_php_timezone = new DateTimeZone($value->getText());
    }

    public static function iCalDeserialize($ical_string)
    {
        new static(new Text($ical_string));
    }
}
