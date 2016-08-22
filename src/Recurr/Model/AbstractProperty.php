<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

use InvalidArgumentException;
use Recurr\RRule;

/**
 * An iCal property.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.5 RFC 5545 Property definition
 * @see https://tools.ietf.org/html/rfc5545#section-3.1 RFC 5545 Property formatting
 */
abstract class AbstractProperty implements Property
{
    /**
     * Constants
     */

    /**
     * The character used to separate the property name and params from the value.
     *
     * @type string
     */
    const PROPERTY_VALUE_SEPERATOR = ':';


    /**
     * Properties
     */

    /**
     * @type Parameter[]
     */
    private $parameter_list = [];

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
     * @param string $value The value of the property.
     * @param Parameter[] $parameter_list The parameters for the property.
     * @throws InvalidArgumentException Thrown when the parameters are invalid for the property.
     */
    public function __construct($value, array $parameter_list = [])
    {
        $this->parameter_list = $parameter_list;
        $this->value = $value;

        if (false === $this->validateParameterList($parameter_list)) {
            throw new InvalidArgumentException(
                'Invalid parameters given to property ' . static::class . ': ' . implode(',', $parameter_list)
            );
        }
    }

    /**
     * Returns Whether the parameters are value for the property.
     *
     * @param Parameter[] $parameter_list The list of parameters to validate for the property.
     * @return boolean
     */
    abstract protected function validateParameterList(array $parameter_list);

    /**
     * {@inheritdoc}
     */
    public function getParameterList()
    {
        return $this->parameter_list;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $string = $this->getName();
        $params = $this->getParameterList();

        foreach ($params as $param) {
            $string .= Parameter::PARAMETER_LIST_DELIMITER . (string) $param;
        }

        return $string . self::PROPERTY_VALUE_SEPERATOR . trim($this->getValue()) . self::CLRF;
    }

    /**
     * Factory method that builds an instance from a raw iCal content-line.
     *
     * @param string $content_line An iCal content-line.
     * @return static
     * @throws InvalidArgumentException Thrown when the given string is not iCal compliant.
     */
    public static function fromString($content_line)
    {
        // TODO: properties can have additional `:` characters if escaped with quotes.
        // Shouldn't encounter this with recurrence properties, tho, but for future reference if this comes up.
        $parts = explode(self::PROPERTY_VALUE_SEPERATOR, $content_line);

        if (2 !== count($parts)) {
            throw new InvalidArgumentException('The property was malformed: ' . $content_line);
        }

        list($name_and_props, $value) = $parts;

        $param_parts = explode(Parameter::PARAMETER_LIST_DELIMITER, $name_and_props);

        // remove the name from the parts
        $name = strtoupper(array_shift($param_parts));

        $params = [];
        foreach ($param_parts as $param) {
            $params[] = Parameter::fromString($param);
        }

        $known_properties = Registries::PROPERTIES;

        if (!isset($known_properties[$name])) {
            throw new UnknownPropertyNameEcception('The property ' . $name . ' is not yet supported by this library.');
        }

        $property_class = $known_properties[$name];

        return new $property_class($value, $parts);
    }
}
