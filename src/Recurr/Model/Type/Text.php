<?php

namespace Recurr\Model\Type;

use Robin\Api\Libraries\Calendar\Model\ICal\Value;

/**
 * This value type is used to identify values that contain human-readable text.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.3.11
 */
final class Text implements Value
{
    private $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function iCalSerialize()
    {
        return $this->text;
    }

    public static function iCalDeserialize($ical_string)
    {
        return static($ical_string);
    }
}
