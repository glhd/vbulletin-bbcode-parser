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
     * @param array $urls
     */
    public function __construct(array $urls = [])
    {
        $this->urls = $urls;
    }

    /**
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        $pattern = '/\[([a-z0-9]+).*?\].*?\[\/\1\]/is';

        return preg_replace_callback($pattern, function (array $match) {
            return $this->parseBlock(
                Arr::get($match, 0), Arr::get($match, 1)
            );
        }, $text);
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

        try {
            $text = $tag->render($block);
        } catch (MissingTagException $e) {
            $this->validateMissingTag($tagName);

            $callable = Arr::get($this->customParsers, $tagName);
            $text = $tag->renderCustom($block, $callable);
        }

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

    /**
     * @param string $tagName
     * @throws MissingTagException
     */
    private function validateMissingTag($tagName)
    {
        if (!isset($this->customParsers[$tagName])) {
            throw new MissingTagException($tagName);
        }
    }
}
