<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Parser;
use Galahad\Bbcode\Tag;
use Illuminate\Support\Collection;
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
    public function fetchTags()
    {
        $text = 'This is a [color=red]red color[/color] text.';
        $tags = (new Parser($text))->fetchTags();

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertInstanceOf(Tag::class, $tags->first());
        $this->assertEquals('color', $tags->first()->getName());
        $this->assertEquals('red', $tags->first()->getAttribute());
        $this->assertEquals('red color', $tags->first()->getContent());
    }
}
