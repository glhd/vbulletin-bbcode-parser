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
    protected $originalText;

    /**
     * @param string $originalText
     */
    public function __construct($originalText)
    {
        $this->originalText = $originalText;
    }

    public function toHtml()
    {
        //
    }

    public function parse()
    {

    }

    /**
     * @return Collection
     */
    protected function fetchTags()
    {
        preg_match_all($this->pattern, $this->originalText, $matches, PREG_SET_ORDER);

        return collect($matches)->map(function (array $match) {
            list(, $tag, , $attribute, $content) = $match;

            return new Tag($tag, $content, $attribute ?: null);
        });
    }
}
