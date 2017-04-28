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
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        $blocks = $this->parseBlocks($text);

        foreach ($blocks as $block) {
            preg_match('/\[(.+)\]/', $block, $match);
            list(, $name) = $match;

            $tag = new Tag($name, $block);
            $text = str_replace($block, $tag->render(), $text);
        }

        return $text;
    }

    /**
     * @param string $text
     * @return array
     */
    private function parseBlocks($text)
    {
        $blocks = [];
        $block = '';
        $collecting = false;
        $closing = false;

        for ($i = 0; $i < strlen($text); $i++) {
            if ($text[$i] === '[' && !$collecting) {
                $collecting = true;
            }
            if ($text[$i] === '/' && $text[$i - 1] === '[') {
                $closing = true;
            }
            if ($collecting) {
                $block .= $text[$i];
            }
            if ($text[$i] === ']' && $closing) {
                $blocks[] = $block;
                $block = '';
                $collecting = false;
                $closing = false;
            }
        }

        return $blocks;
    }
}
