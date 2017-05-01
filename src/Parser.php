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
        $blocks = $this->extractBlocks($text);

        foreach ($blocks as $name => $block) {
            $tag = new Tag($name, $block);
            $text = str_replace($block, $tag->render(), $text);
        }

        return $text;
    }

    /**
     * @param string $text
     * @return array
     */
    private function extractBlocks($text)
    {
        $pattern = '/\[[^\]]+\].+?\[\/([\w\d]+)\]/is';
        preg_match_all($pattern, $text, $matches);

        $keys = Arr::get($matches, 1);
        $values = Arr::get($matches, 0);

        return array_combine($keys, $values);
    }
}
