<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Class ParserTest
 *
 * @package Galahad\Bbcode\Tests
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ParserTest extends TestCase
{
    /**
     * @test
     */
    public function parseColor()
    {
        $text = 'This is a [color=red]red color[/color] text.';
        $parser = new Parser($text);

        $this->assertEquals(
            'This is a <span style="color: red;">red color</span> text.',
            $parser->toHtml()
        );
    }
}
