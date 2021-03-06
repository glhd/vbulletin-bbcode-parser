<?php

namespace Galahad\Bbcode;

use Galahad\Bbcode\Exception\MissingTagException;
use Illuminate\Support\Arr;

/**
 * Class Parser
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Parser
{
    /**
     * @var array
     */
    protected $urls = [];

    /**
     * @var array
     */
    protected $customParsers = [];

    /**
     * @var EmojiParser
     */
    protected $emojiParser;

    /**
     * @param array $urls
     */
    public function __construct(array $urls = [])
    {
        $this->urls = $urls;
        $this->emojiParser = new EmojiParser();
    }

    /**
     * @param string $text
     * @param bool $parseEmoji
     * @return string
     */
    public function parse($text, $parseEmoji = false)
    {
        $pattern = '/\[([a-z0-9]+)[^\[]*(?:\[(?!\1\b)[^\[]*)*?\[\/\1\]/is';

        return preg_replace_callback($pattern, function (array $match) {
            return $this->parseBlock(
                Arr::get($match, 0), Arr::get($match, 1)
            );
        }, $parseEmoji ? $this->emojiParser->parse($text) : $text);
    }

    /**
     * @param string $block
     * @param string $tagName
     * @return string
     * @throws MissingTagException
     */
    protected function parseBlock($block, $tagName)
    {
        $tag = new Tag($tagName, $this->urls);
        $tag->setCustomParsers($this->customParsers);

        $text = $tag->render($block);

        if ($tag->hasChildren()) {
            return $this->parse($text);
        }

        return $text;
    }

    /**
     * @param string $tag
     * @param $callable
     */
    public function extend($tag, $callable)
    {
        $this->customParsers[$tag] = $callable;
    }
}
