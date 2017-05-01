<?php

namespace Galahad\Bbcode;

use Illuminate\Support\Arr;
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
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        $blocks = $this->extractTags($text);

        foreach ($blocks as $name) {
            $tag = new Tag($name, $text);
            $text = $tag->render();
        }

        return $text;
    }

    /**
     * @param string $text
     * @return array
     */
    private function extractTags($text)
    {
        $pattern = '/\[\/([\w\d]+)\]/i';
        preg_match_all($pattern, $text, $matches);

        $tags = Arr::get($matches, 1);

        return array_map('strtolower', $tags);
    }
}
