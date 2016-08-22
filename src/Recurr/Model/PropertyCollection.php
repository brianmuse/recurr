<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

use InvalidArgumentException;
use SplFixedArray;

/**
 * An collection of iCal properties.
 */
class PropertyCollection extends SplFixedArray
{
    /**
     * The newline character that separates iCal properties.
     */
    const ICAL_LINE_SEPARATOR = PHP_EOL;

    /**
     * Factory method to build the collection from a string.
     *
     * The string will be broken up into an array of new lines and built from the array.
     *
     * @see self::ICAL_LINE_SEPARATOR
     *
     * @param string $properties_string The properties, represented as a serialized iCal string.
     * @return static
     */
    public static function fromString($properties_string)
    {
        // Handles iCal folding
        // TODO: Consider moving elsewhere if we need to reuse this.
        $string_array = array_reduce(
            explode(self::ICAL_LINE_SEPARATOR, trim($properties_string, self::ICAL_LINE_SEPARATOR)),
            function (array $carry, $line) {
                $trimmed = rtrim($line);
                // If we have an empty line, just continue.
                if (empty($trimmed)) {
                    return $carry;
                }

                // If the line's first character is a tab or a space, append the rest of the line to the end of the
                // previous line.
                if (!empty($carry) && ("\t" === $line[0] || " " === $line[0])) {
                    $carry[count($carry) - 1] .= substr($line, 1, strlen($line));
                } else {
                    // Otherwise just add this new line to the carry, but trim leading whitespace.
                    $carry[] = ltrim($line);
                }

                return $carry;
            },
            []
        );

        return static::fromStringArray($string_array);
    }

    /**
     * Factory method to build the collection from an array
     *
     * @param array[] $properties An array of iCal properties, such as `RRULE:...`.
     * @return static
     */
    public static function fromStringArray(array $properties = [])
    {
        $property_objects = array_map(function ($property) {
            return AbstractProperty::fromString($property);
        }, $properties);

        return static::fromArray($property_objects);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public static function fromArray($array, $save_indexes = true)
    {
        $collection = new static(count($array));

        for ($i = 0; $i < $collection->getSize(); $i++) {
            $collection[$i] = $array[$i];
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException Thrown when the object being added isn't the correct type.
     */
    public function offsetSet($index, $newval)
    {
        if (!$newval instanceof Property) {
            throw new InvalidArgumentException('Object must be an instance of ' . Property::class);
        }

        parent::offsetSet($index, $newval);
    }

    /**
     * Returns the collection as an array with all the properties serialized to strings.
     *
     * This is the functional inverse of {@see fromStringArray()}.
     *
     * @return array
     */
    public function toStringArray()
    {
        return array_map(function (Property $property) {
            return (string) $property;
        }, $this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return implode(PHP_EOL, $this->toArray());
    }
}
