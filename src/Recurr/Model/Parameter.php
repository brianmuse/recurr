<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

use InvalidArgumentException;
use const Recurr\PARAMETER_VALUE_SEPERATOR;

/**
 * An iCal property parameter.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.2 RFC 5545 Property Parameter Definition
 */
final class Parameter implements ICalSerializable
{
    /**
     * Constants
     */


    /**
     * Properties
     */

    /**
     * @type string
     */
    private $name;

    /**
     * @type string
     */
    private $value;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param string $name The name of the parameter.
     * @param string $value The value for the parameter.
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function iCalSerialize()
    {
        return $this->getName() . PARAMETER_VALUE_SEPERATOR . $this->getValue();
    }

    /**
     * Generates an instance from a parameter string in the format `PARAM_NAME=PARAM_VALUE`.
     *
     * @param string $parameter_string The full parameter string.
     * @return static
     * @throws InvalidArgumentException Thrown when the given string is not iCal compliant.
     */
    public static function fromString($parameter_string)
    {
        $parts = explode(self::PARAMETER_VALUE_SEPERATOR, $parameter_string);

        if (2 !== count($parts)) {
            throw new InvalidArgumentException('The parameter was malformed: ' . $parameter_string);
        }

        return new static ($parts[0], $parts[1]);
    }
}
