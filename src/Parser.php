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
    protected $pattern = '/\[(\w+)(="?([\w\d\s\.@_+-]+)"?)?\]([^\[]+)\[\/\w+\]/i';

    /**
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        preg_match_all(
            $this->pattern, $text, $matches, PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            list($string, $name, , $attribute, $content) = $match;

            $tag = new Tag($name, $content, $attribute);
            $text = str_replace($string, $tag->render(), $text);
        }

        return $text;
    }
}
