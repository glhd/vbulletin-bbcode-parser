<?php

namespace Galahad\Bbcode;

use Illuminate\Support\Collection;

/**
 * Class Parser
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Parser
{
    /**
     * @var string
     */
    protected $pattern = '/\[(\w+)(="?([\w\+]+)"?)?\]([^\[]+)\[\/\w+\]/i';

    /**
     * @var string
     */
    protected $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $text = $this->text;

        preg_match_all(
            $this->pattern, $this->text, $matches, PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            list($string, $name, , $attribute, $content) = $match;
            $tag = new Tag($string, $name, $content, $attribute ?: null);
            $text = str_replace($string, $tag->toHtml(), $text);
        }

        return $text;
    }
}
