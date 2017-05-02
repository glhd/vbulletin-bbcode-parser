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
    public function fontWithSizeTag()
    {
        $actual = '[FONT=Times New Roman][SIZE=3]text here.[/SIZE][/FONT]';
        $expected = '<span style="font-family: Times New Roman;"><span style="font-size: 100%;">text here.</span></span>';

        $this->assertEquals($expected, $this->parser()->parse($actual));
    }

    /**
     * @test
     */
    public function realCaseWithFontAndColorTags()
    {
        $input = <<<INPUT
[B][COLOR=royalblue]This looks great, all I have to do is learn how to use it. [/COLOR][/B]
[B][COLOR=#4169e1][/COLOR][/B] 
[B][COLOR=#4169e1]Great job ladies and gentlemen.[/COLOR][/B]
[B][COLOR=#4169e1][/COLOR][/B] 
[B][COLOR=#4169e1]Marcel [/COLOR][/B]
INPUT;

        $output = <<<OUTPUT
<strong><span style="color: royalblue;">This looks great, all I have to do is learn how to use it. </span></strong>
<strong><span style="color: #4169e1;"></span></strong> 
<strong><span style="color: #4169e1;">Great job ladies and gentlemen.</span></strong>
<strong><span style="color: #4169e1;"></span></strong> 
<strong><span style="color: #4169e1;">Marcel </span></strong>
OUTPUT;

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @return Parser
     */
    private function parser()
    {
        return new Parser();
    }
}
