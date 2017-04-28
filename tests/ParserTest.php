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

        $this->assertEquals(
            'This is a <span style="color: red;">red color</span> text.',
            $this->parser()->parse($text)
        );
    }

    /**
     * @test
     */
    public function parseSize()
    {
        $tests = [
            '1' => '60%', '2' => '89%', '3' => '100%', '4' => '120%', '5' => '150%',
            '6' => '200%', '7' => '300%', '+1' => '120%', '+2' => '150%', '-1' => '89%',
            '-2' => '60%', 'medium' => 'medium', 'small' => 'small', 'x-large' => 'x-large',
        ];

        foreach ($tests as $size => $expected) {
            $this->assertEquals(
                "<span style=\"font-size: $expected;\">This is awesome</span>",
                $this->parser()->parse("[size=$size]This is awesome[/size]")
            );
        }
    }

    /**
     * @test
     */
    public function parseFont()
    {
        $this->assertEquals(
            '<span style="font-family: Times New Roman;">Testing</span>',
            $this->parser()->parse('[font=Times New Roman]Testing[/font]')
        );
    }

    /**
     * @test
     */
    public function parseHighlight()
    {
        $this->assertEquals(
            'this is a <mark>test text</mark>',
            $this->parser()->parse('this is a [highlight]test text[/highlight]')
        );
    }

    /**
     * @test
     */
    public function parseTextAlignment()
    {
        $text = 'just a test text';
        $positions = ['left', 'center', 'right'];

        foreach ($positions as $position) {
            $this->assertEquals(
                "<div style=\"text-align: $position;\">$text</div>",
                $this->parser()->parse("[{$position}]{$text}[/{$position}]")
            );
        }
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

    /**
     * @return Parser
     */
    private function parser()
    {
        return new Parser();
    }
}
