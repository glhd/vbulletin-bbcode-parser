<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Exception\MissingTagException;
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
        $parser = new Parser();

        $this->assertEquals(
            'This is a <span style="color: red;">red color</span> text.',
            $parser->parse($text)
        );
    }

    /**
     * @test
     */
    public function missingTagException()
    {
        $this->expectException(MissingTagException::class);

        $parser = new Parser();
        $parser->parse('Text [fake]to fail[/fake]');
    }
}
