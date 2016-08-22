<?php

namespace Recurr\Test;

use PHPUnit_Framework_TestCase;
use function Recurr\fold;
use function Recurr\unfold;

/**
 * @group Parsing
 */
class ParseFnTest extends PHPUnit_Framework_TestCase
{
    public function testFoldForSingleLine()
    {
        // 175 characters
        $test_string = "RDATE:20160101T000000Z,20160102T000000Z,20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r";

        $result = fold($test_string);

        $lines = explode("\n\r", $result);

        $this->assertCount(4, $lines); // Three lines (two folded) + the final newline.

        foreach ($lines as $index => $line) {
            // Check that each folded line has a space in front and is less than 75 characters.
            if (1 === $index ||  2 === $index) {
                $this->assertSame(' ', substr($line, 0, 1));
            }
            $this->assertLessThanOrEqual(75, strlen($line));
        }
    }

    public function testFoldForSingleLineWithTab()
    {
        $test_string = "RDATE:20160101T000000Z,20160102T000000Z,20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,20160108T000000Z,20160109T000000Z,20160110T000000Z";

        $result = fold($test_string, true);

        $lines = explode("\n\r", $result);

        foreach ($lines as $index => $line) {
            // Check that each folded line has a tab as the first character.
            if (1 === $index ||  2 === $index) {
                $this->assertSame("\t", substr($line, 0, 1));
            }
        }
    }

    public function testFoldForMultiLineComplexString()
    {
        // Long content line followed by short content line followed by already folded content line with one line
        // still too long..
        $test_string = "RDATE:20160101T000000Z,20160102T000000Z,20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r"
            . "RDATE:20160101T000000Z,20160102T000000Z\n\r"
            . "EXDATE:20160101T000000Z,20160102T000000Z,\n\r 20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,\n\r\t20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r";

        $result = fold($test_string);

        $lines = explode("\n\r", $result);

        $this->assertCount(9, $lines);

        $expected_folded_lines = [1, 2, 5, 6];

        foreach ($lines as $index => $line) {
            // Check that each line has a space in front and is less than 75 characters.
            if (in_array($index, $expected_folded_lines)) {
                $this->assertRegExp('/^[\s\t].*$/', substr($line, 0, 1));
            }
            $this->assertLessThanOrEqual(75, strlen($line));
        }
    }

    public function testUnfoldForSingleLine()
    {
        $folded = "RDATE:20160101T000000Z,20160102T000000Z,\n\r 20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,\n\r\t20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r";

        $expected_result = "RDATE:20160101T000000Z,20160102T000000Z,20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r";

        $result = unfold($folded);

        $this->assertSame($expected_result, $result);
    }

    public function testUnfoldForMulpliLineComplexString()
    {
        $folded = "RDATE:20160101T000000Z,20160102T000000Z,\n\r 20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "\n\r 20160106T000000Z,20160107T000000Z,\n\r 20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r"
            . "RDATE:20160101T000000Z,\n\r 20160102T000000Z\n\r"
            . "EXDATE:20160101T000000Z,\n\r 20160102T000000Z,\n\r 20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "\n\r 20160106T000000Z,20160107T000000Z,\n\r\t20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r";

        $expected_result = "RDATE:20160101T000000Z,20160102T000000Z,20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r"
            . "RDATE:20160101T000000Z,20160102T000000Z\n\r"
            . "EXDATE:20160101T000000Z,20160102T000000Z,20160103T000000Z,20160104T000000Z,20160105T000000Z,"
            . "20160106T000000Z,20160107T000000Z,20160108T000000Z,20160109T000000Z,20160110T000000Z\n\r";

        $result = unfold($folded);

        $this->assertSame($expected_result, $result);
    }
}

