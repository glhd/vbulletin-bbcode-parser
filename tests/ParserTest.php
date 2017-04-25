<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Parser;
use Galahad\Bbcode\Tag;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

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
        $tags = $this->callParserMethod($text, 'fetchTags');

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertInstanceOf(Tag::class, $tags->first());

        $this->assertEquals('[color=red]red color[/color]', $tags->first()->getTag());
        $this->assertEquals('color', $tags->first()->getName());
        $this->assertEquals('red', $tags->first()->getAttribute());
        $this->assertEquals('red color', $tags->first()->getContent());
    }

    /**
     * @param string $text
     * @param string|null $method
     * @param array $parameters
     * @return mixed
     */
    private function callParserMethod($text, $method = null, $parameters = [])
    {
        $reflection = new ReflectionClass(Parser::class);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs(new Parser($text), $parameters);
    }
}
