<?php

namespace Recurr;

use Robin\Api\Libraries\Calendar\Model\ICal\Property;
use Robin\Api\Libraries\Calendar\Model\ICal\UnknownPropertyNameEcception;

function decodeICalendar($content_lines)
{
    $unfolded = unfold($content_lines);

    $lines = explode(CLRF, $unfolded);

    $properties = [];

    foreach ($lines as $content_line) {
        try {

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

            $property_class = $known_properties[$name];

            $properties[] = new $property_class($value, $parts);
        } catch (UnknownPropertyNameEcception $exception) {
            // Just ignore properties that are unknown.
            continue;
        }
    }

    return $properties;
}

function encodeICalendar(array $properties)
{
    $result = '';

    foreach ($properties as $property) {
        $result .= encodeProperty($property);
    }

    return $result;
}

function encodeProperty(Property $property)
{
    $string = $this->getName();
    $params = $this->getParameterList();

    foreach ($params as $param) {
        $string .= PARAMETER_LIST_DELIMITER . encodeParam($param);
    }

    return $string . PROPERTY_VALUE_SEPERATOR . trim($this->getValue()) . CLRF;
}

function encode

function fold($lines, $use_tab = false)
{
    $lines_array = explode(CLRF, $lines);

    return array_reduce($lines_array, function ($carry, $line) use ($use_tab) {
        $line = rtrim($line);

        if (empty($line)) {
            return $carry;
        }

        $parts = str_split($line, 74); // 74 to allow for a space for folded lines.

        return $carry. implode(CLRF . ($use_tab ? "\t" : " "), $parts) . CLRF;
    }, '');
}

function unfold($lines)
{
    $parts = explode(CLRF, $lines);

    $result = array_reduce($parts, function ($carry, $part) {
        if (preg_match('/^[\s\t].*$/', $part)) {
            // remove only the first character (should be a space or tab).
            $part = substr($part, 1, strlen($part) - 1);
            $carry .= $part;
        } else {
            $carry .= CLRF . $part;
        }

        return $carry;
    }, '');

    return ltrim($result);
}
