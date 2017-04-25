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
     * @var Collection
     */
    protected $tags;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
        $this->tags = $this->fetchTags();
    }

    public function toHtml()
    {

    }

    public function parse()
    {

    }

    /**
     * @return Collection
     */
    protected function fetchTags()
    {
        preg_match_all($this->pattern, $this->text, $matches, PREG_SET_ORDER);

        return collect($matches)->map(function (array $match) {
            list($tag, $name, , $attribute, $content) = $match;

            return new Tag(
                $tag, $name, $content, $attribute ?: null
            );
        });
    }
}
