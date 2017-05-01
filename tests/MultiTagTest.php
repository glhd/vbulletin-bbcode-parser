<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Class MultiTagTest
 *
 * @package Galahad\Bbcode\Tests
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class MultiTagTest extends TestCase
{
    /**
     * @test
     */
    public function parseFontWithSizeTag()
    {
        $actual = '[FONT=Times New Roman][SIZE=3]text here.[/SIZE][/FONT]';
        $expected = '<span style="font-family: Times New Roman;"><span style="font-size: 100%;">text here.</span></span>';

        $this->assertEquals($expected, $this->parser()->parse($actual));
    }

    /**
     * @return Parser
     */
    private function parser()
    {
        return new Parser();
    }
}
