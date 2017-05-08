<?php

namespace Galahad\Bbcode;

use Closure;
use Galahad\Bbcode\Exception\MissingAttributeException;
use Galahad\Bbcode\Exception\MissingTagException;
use Galahad\Bbcode\Exception\MissingUrlException;
use Galahad\Bbcode\Tags\ListTag;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Tag
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 * @see http://www.vbulletin.org/forum/misc.php?do=bbcode
 */
class Tag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $block;

    /**
     * @var mixed
     */
    protected $content;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $urls;

    /**
     * @param string $name
     * @param array $urls
     */
    public function __construct($name, array $urls = [])
    {
        $this->name = $name;
        $this->urls = $urls;
    }

    /**
     * @return string
     */
    public function tagB()
    {
        return sprintf('<strong>%s</strong>', $this->content);
    }

    /**
     * @return string
     */
    public function tagI()
    {
        return sprintf('<em>%s</em>', $this->content);
    }

    /**
     * @return string
     */
    public function tagU()
    {
        return sprintf('<u>%s</u>', $this->content);
    }

    /**
     * @return string
     */
    public function tagS()
    {
        return sprintf('<s>%s</s>', $this->content);
    }

    /**
     * @return string
     */
    public function tagColor()
    {
        $this->validateAttribute();

        return sprintf(
            '<span style="color: %s;">%s</span>',
            Arr::first($this->attributes),
            $this->content
        );
    }

    /**
     * @return string
     * @see http://style.cleverchimp.com/font_size_intervals/altintervals.html#bbs
     */
    public function tagSize()
    {
        $this->validateAttribute();

        $attribute = Arr::first($this->attributes);
        $scale = [60, 89, 100, 120, 150, 200, 300];
        $position = intval($attribute) - 1; // -1 -1 or +1 - 1

        if (in_array($attribute[0], ['-', '+'])) {
            $position = 3 + $position;
        }

        return sprintf(
            '<span style="font-size: %s;">%s</span>',
            isset($scale[$position]) ? $scale[$position] . '%' : $attribute,
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagFont()
    {
        $this->validateAttribute();

        return sprintf(
            '<span style="font-family: %s;">%s</span>',
            Arr::first($this->attributes),
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagHighlight()
    {
        return sprintf(
            '<mark>%s</mark>',
            $this->content
        );
    }

    /**
     * @param string $position
     * @return string
     */
    protected function renderTextAlignment($position = 'left')
    {
        return sprintf(
            '<div style="text-align: %s;">%s</div>',
            $position,
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagLeft()
    {
        return $this->renderTextAlignment('left');
    }

    /**
     * @return string
     */
    public function tagCenter()
    {
        return $this->renderTextAlignment('center');
    }

    /**
     * @return string
     */
    public function tagRight()
    {
        return $this->renderTextAlignment('right');
    }

    /**
     * @return string
     */
    public function tagIndent()
    {
        return sprintf(
            '<blockquote><div>%s</div></blockquote>',
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagEmail()
    {
        $attribute = Arr::first($this->attributes);

        return sprintf(
            '<a href="mailto:%s">%s</a>',
            $attribute ?: $this->content,
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagUrl()
    {
        $attribute = Arr::first($this->attributes);

        return sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $attribute ?: $this->content,
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagThread()
    {
        $id = Arr::get($this->attributes, 'thread', $this->content);
        $url = $this->fetchUrl('thread_url', $id);
        $content = $this->content === $id ? $url : $this->content;

        return sprintf('<a href="%s">%s</a>', $url, $content);
    }

    /**
     * @return string
     */
    public function tagPost()
    {
        $id = Arr::get($this->attributes, 'post', $this->content);
        $url = $this->fetchUrl('post_url', $id);
        $content = $this->content === $id ? $url : $this->content;

        return sprintf('<a href="%s">%s</a>', $url, $content);
    }

    /**
     * @param string $text
     * @return string
     */
    public function tagList($text)
    {
        $attribute = Arr::first($this->attributes);

        return (new ListTag($attribute))->render($text);
    }

    /**
     * @return string
     */
    public function tagImg()
    {
        return sprintf(
            '<img class="" src="%s"/>',
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagCode()
    {
        return sprintf(
            '<code><pre>%s</pre></code>',
            $this->content
        );
    }

    /**
     * @return string
     * @todo Syntax highlighting
     */
    public function tagPhp()
    {
        return $this->tagCode();
    }

    /**
     * @return string
     * @todo Syntax highlighting
     */
    public function tagHtml()
    {
        return $this->tagCode();
    }

    /**
     * @return string
     */
    public function tagQuote()
    {
        $footer = Arr::get($this->attributes, 'quote', '');

        if (strpos($footer, ';') !== false) {
            list($name, $postId) = explode(';', $footer);
            $url = $this->fetchUrl('post_url', $postId);
            $footer = sprintf('<a href="%s">%s</a>', $url, $name);
        }

        $html = <<<HTML
<blockquote>
    <p>%s</p>
    <footer>%s</footer>
</blockquote>
HTML;

        return sprintf($html, $this->content, $footer);
    }

    /**
     * @return string
     */
    public function tagNoparse()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function tagAttach()
    {
        $url = $this->fetchUrl('attach_url', $this->content);

        return sprintf('<a href="%1$s">%1$s</a>', $url);
    }

    /**
     * @return string
     */
    public function tagA()
    {
        return sprintf(
            '<a href="%s">%s</a>',
            Arr::first($this->attributes),
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagAlign()
    {
        $this->validateAttribute();

        return $this->renderTextAlignment(
            Arr::first($this->attributes)
        );
    }

    /**
     * @param string $text
     * @param string $position
     * @return string
     */
    public function tagFloatright($text, $position = 'right')
    {
        return sprintf(
            '<div style="float: %s;">%s</div>',
            $position,
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagH2()
    {
        return '<h2>' . $this->content . '</h2>';
    }

    /**
     * @return string
     */
    public function tagH3()
    {
        return '<h3>' . $this->content . '</h3>';
    }

    /**
     * @return string
     */
    public function tagHigh()
    {
        return '<mark>' . $this->content . '</mark>';
    }

    /**
     * @return string
     */
    public function tagHr()
    {
        return '<hr />' . $this->content . '<hr />';
    }

    /**
     * @param string $text
     * @param string $position
     * @return string
     */
    public function tagImglft($text, $position = 'left')
    {
        return sprintf(
            '<img src="%s" alt="" style="float: %s;">',
            $this->content,
            $position
        );
    }

    /**
     * @param string $text
     * @return string
     */
    public function tagImgrft($text)
    {
        return $this->tagImglft($text, 'right');
    }

    /**
     * @return string
     */
    public function tagJira()
    {
        return sprintf(
            '<a href="%s">%s</a>',
            $this->fetchUrl('jira_url', $this->content),
            strtoupper($this->content)
        );
    }

    /**
     * @param string $text
     * @return string
     */
    public function tagLft($text)
    {
        return $this->tagFloatright($text, 'left');
    }

    /**
     * @return string
     */
    public function tagMinicode()
    {
        // TODO

        return $this->block;
    }

    /**
     * @return string
     */
    public function tagName()
    {
        $url = $this->fetchUrl('user_url', strtolower($this->content));

        return sprintf('<a href="%s">%s</a>', $url, $this->content);
    }

    /**
     * @return string
     */
    public function tagNode()
    {
        // TODO

        return $this->block;
    }

    /**
     * @return string
     */
    public function tagNote()
    {
        return sprintf('<div class="alert alert-info">%s</div>', $this->content);
    }

    /**
     * @return string
     */
    public function tagPre()
    {
        return sprintf('<pre>%s</pre>', $this->content);
    }

    /**
     * @return string
     */
    public function tagProcess()
    {
        $html = '<ol class="breadcrumb">';
        $items = array_map('trim', explode('>', $this->content));

        foreach ($items as $i => $item) {
            if ($i === count($items) - 1) {
                $html .= sprintf('<li class="active">%s</li>', $item);
            } else {
                $html .= sprintf('<li><a>%s</a></li>', $item);
            }
        }

        return $html . '</ol>';
    }

    /**
     * @param string $text
     * @return string
     */
    public function tagRft($text)
    {
        return $this->tagFloatright($text, 'right');
    }

    /**
     * @throws MissingAttributeException
     */
    protected function validateAttribute()
    {
        if (empty(trim(Arr::first($this->attributes)))) {
            throw new MissingAttributeException();
        }
    }

    /**
     * @param string $key
     * @param null $id
     * @return string
     * @throws MissingUrlException
     */
    protected function fetchUrl($key, $id = null)
    {
        if ($url = Arr::get($this->urls, $key)) {
            if ($id !== null) {
                $parameter = str_replace('_url', '_id', $key);
                $url = str_replace('{' . $parameter . '}', $id, $url);
            }

            return $url;
        }

        throw new MissingUrlException();
    }

    /**
     * @param string $text
     * @return string
     * @throws MissingTagException
     * @todo Allow users to override a tag using a custom class or Closure
     */
    public function render($text)
    {
        $method = 'tag' . ucfirst($this->name);

        if (method_exists($this, $method)) {
            if ($this->split($text)) {
                return $this->$method($text);
            }

            return $text;
        }

        throw new MissingTagException($this->name);
    }

    /**
     * @param string $block
     * @param $callable
     * @return string
     */
    public function renderCustom($block, $callable)
    {
        $this->split($block);

        if (is_string($callable)) {
            return (new $callable())
                ->render($block, $this->attributes, $this->content);
        }

        return call_user_func_array($callable, [
            $block,
            $this->attributes,
            $this->content
        ]);
    }

    /**
     * @param string $string
     * @return array
     */
    protected function splitAttributes($string)
    {
        $pattern = '/([\w\d]+)=("([^ ]+)"|[^"\]]+)/i';
        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);
        $attributes = [];

        foreach ($matches as $match) {
            $key = Arr::get($match, 1);
            $attributes[strtolower($key)] = Arr::last($match);
        }

        return $attributes;
    }

    /**
     * @internal param string $tag
     * @param string $block
     * @return bool
     */
    protected function split($block)
    {
        $pattern = '/\[(%s[^\]]*)\](.*?)\[\/%s\]/is';
        $pattern = sprintf($pattern, $this->name, $this->name);

        preg_match($pattern, $block, $match);

        $block = Arr::get($match, 0, '');
        $attributes = Arr::get($match, 1, '');
        $this->content = Arr::get($match, 2, '');

        $this->attributes = $this->splitAttributes($attributes);
        $this->block = $block;

        return (bool)$block;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        $pattern = '/\[([a-z0-9]+).*?\].*?\[\/\1\]/is';

        return preg_match($pattern, $this->content)
            && $this->name !== 'noparse';
    }
}
