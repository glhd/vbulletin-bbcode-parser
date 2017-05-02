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
     * @test
     */
    public function realCaseFontTagWithColor()
    {
        $input = <<<INPUT
[FONT=Arial Black][COLOR=royalblue]This is great, now I have to figure out where the post I typed a few minutes ago went, where is the 6 year old tutor when you need one. [/COLOR][/FONT]
[FONT=Arial Black][COLOR=#4169e1][/COLOR][/FONT] 
[FONT=Arial Black][COLOR=#4169e1]Marcel [/COLOR][/FONT]
INPUT;

        $output = <<<OUTPUT
<span style="font-family: Arial Black;"><span style="color: royalblue;">This is great, now I have to figure out where the post I typed a few minutes ago went, where is the 6 year old tutor when you need one. </span></span>
<span style="font-family: Arial Black;"><span style="color: #4169e1;"></span></span> 
<span style="font-family: Arial Black;"><span style="color: #4169e1;">Marcel </span></span>
OUTPUT;

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @test
     */
    public function realCaseFontWithCaseAndSizeTags()
    {
        $input = <<<INPUT
[FONT=Comic Sans MS][SIZE=3][COLOR=red]Wow big changes to learn.[/COLOR][/SIZE][/FONT]
[FONT=Comic Sans MS][SIZE=3][COLOR=red][/COLOR][/SIZE][/FONT] 
[SIZE=3][COLOR=red][FONT=Comic Sans MS]Anyway it looks good lets get some electrical questions in here[/FONT].[/COLOR][/SIZE]  :)
INPUT;

        $output = <<<OUTPUT
<span style="font-family: Comic Sans MS;"><span style="font-size: 100%;"><span style="color: red;">Wow big changes to learn.</span></span></span>
<span style="font-family: Comic Sans MS;"><span style="font-size: 100%;"><span style="color: red;"></span></span></span> 
<span style="font-size: 100%;"><span style="color: red;"><span style="font-family: Comic Sans MS;">Anyway it looks good lets get some electrical questions in here</span>.</span></span>  :)
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
