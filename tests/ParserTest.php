<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Parser;
use Galahad\Bbcode\Tag;
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
    public function parseInstance()
    {
        $text = 'This is a [color=red]red color[/color] text.';
        $tag = (new Parser($text))->parse();

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals('color', $tag->getName());
        $this->assertEquals('red', $tag->getAttribute());
        $this->assertEquals('red color', $tag->getContent());
    }
}