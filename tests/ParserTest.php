<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Exception\MissingAttributeException;
use Galahad\Bbcode\Exception\MissingTagException;
use Galahad\Bbcode\Parser;
use PHPUnit\Framework\TestCase;

// TODO test number of blocks found in a text

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
        $colors = ['red', '#ff0000'];

        foreach ($colors as $color) {
            $text = "this is a [color=$color]colored[/color] word";
            $this->assertEquals(
                "this is a <span style=\"color: $color;\">colored</span> word",
                $this->parser()->parse($text)
            );
        }

        // Missing color attribute
        $this->expectException(MissingAttributeException::class);
        $this->parser()->parse('[color]text[/color]');
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

        // Missing size attribute
        $this->expectException(MissingAttributeException::class);
        $this->parser()->parse('[size]text[/size]');
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

        // Missing font attribute
        $this->expectException(MissingAttributeException::class);
        $this->parser()->parse('[font]text[/font]');
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
    public function parseIndent()
    {
        $this->assertEquals(
            '<blockquote><div>this text is indented</div></blockquote>',
            $this->parser()->parse('[indent]this text is indented[/indent]')
        );
    }

    /**
     * @test
     */
    public function parseEmail()
    {
        $this->assertEquals(
            '<a href="mailto:foo@bar.com">foo@bar.com</a>',
            $this->parser()->parse('[email]foo@bar.com[/email]')
        );

        $this->assertEquals(
            '<a href="mailto:foo@bar.com">click me</a>',
            $this->parser()->parse('[email=foo@bar.com]click me[/email]')
        );
    }

    /**
     * @test
     */
    public function parseUrl()
    {
        $urls = ['http://foo.com', 'http://bar.com?foo=bar%20&foo#bar'];

        foreach ($urls as $url) {
            $this->assertEquals(
                "<a href=\"$url\" target=\"_blank\">$url</a>",
                $this->parser()->parse("[url]{$url}[/url]")
            );

            $this->assertEquals(
                "<a href=\"$url\" target=\"_blank\">foo url</a>",
                $this->parser()->parse("[url={$url}]foo url[/url]")
            );
        }

        $this->assertEquals(
            '<a href="http://foo.bar" target="_blank">foo.bar</a>',
            $this->parser()->parse('[url="http://foo.bar"]foo.bar[/url]')
        );
    }

    /**
     * @test
     */
    public function parseBulletList()
    {
        $list =<<<TEXT
[list] 
[*] list item 1
[*] list item 2
[/list]
TEXT;

        $this->assertEquals(
            '<ul><li>list item 1</li><li>list item 2</li></ul>',
            $this->parser()->parse($list)
        );
    }

    /**
     * @test
     */
    public function parseAdvancedList()
    {
        $input = <<<INPUT
[list=1]
[*]list item 1
[*]list item 2
[/list]
INPUT;

        $output = '<ol type="1"><li>list item 1</li><li>list item 2</li></ol>';

        $this->assertEquals($output, $this->parser()->parse($input));

        $input = <<<INPUT
[list=a]
[*]list item 1
[*]list item 2
[/list]
INPUT;

        $output = '<ol type="a"><li>list item 1</li><li>list item 2</li></ol>';

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @test
     */
    public function parseImg()
    {
        $input = '[img]http://example.com/assets/image.gif[/img]';
        $output = '<img class="" src="http://example.com/assets/image.gif"/>';

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @test
     */
    public function parseCode()
    {
        $input = <<<INPUT
[code]
<script type="text/javascript">
<!--
    alert("Hello world!");
//-->
</script>
[/code]
INPUT;

        $output = <<<OUTPUT
<code><pre>
<script type="text/javascript">
<!--
    alert("Hello world!");
//-->
</script>
</pre></code>
OUTPUT;

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @test
     */
    public function parsePhp()
    {
        $input = <<<'INPUT'
[php]
$myvar = 'Hello World!';
for ($i = 0; $i < 10; $i++)
{
    echo $myvar . "\n";
}
[/php]
INPUT;

        $output = <<<'OUTPUT'
<code><pre>
$myvar = 'Hello World!';
for ($i = 0; $i < 10; $i++)
{
    echo $myvar . "\n";
}
</pre></code>
OUTPUT;

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @test
     */
    public function parseHtml()
    {
        $input = <<<INPUT
[html]
<img src="image.gif" alt="image" />
<a href="testing.html" target="_blank">Testing</a>
[/html]
INPUT;

        $output = <<<OUTPUT
<code><pre>
<img src="image.gif" alt="image" />
<a href="testing.html" target="_blank">Testing</a>
</pre></code>
OUTPUT;

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @test
     */
    public function parseQuote()
    {
        $input = '[quote]Lorem ipsum dolor sit amet[/quote]';
        $output = <<<OUTPUT
<blockquote>
    <p>Lorem ipsum dolor sit amet</p>
    <footer></footer>
</blockquote>
OUTPUT;

        $this->assertEquals($output, $this->parser()->parse($input));

        $input = '[quote=John Doe]Lorem ipsum dolor sit amet[/quote]';
        $output = <<<OUTPUT
<blockquote>
    <p>Lorem ipsum dolor sit amet</p>
    <footer>John Doe</footer>
</blockquote>
OUTPUT;

        $this->assertEquals($output, $this->parser()->parse($input));
    }

    /**
     * @test
     */
    public function parseNoparse()
    {
        $input = '[noparse][b]Lorem ipsum dolor sit amet[/b][/noparse] foo [b]something[/b] bar [b]Lorem ipsum dolor sit amet[/b]';
        $output = '[b]Lorem ipsum dolor sit amet[/b] foo <strong>something</strong> bar <strong>Lorem ipsum dolor sit amet</strong>';

        $this->assertEquals($output, $this->parser()->parse($input));
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
