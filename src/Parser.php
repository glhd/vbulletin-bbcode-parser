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
    const STATE_STOP = 0;
    const STATE_OPEN = 1;
    const STATE_PARAMETER = 2;
    const STATE_CONTENT = 3;
    const STATE_CLOSE = 4;

    /**
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        $state = static::STATE_STOP;
        $block = $newText = $openTag = $closeTag = '';

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            if ($char == '[' && $state == static::STATE_STOP) {
                $state = static::STATE_OPEN;
            }
            if (in_array($char, ['=', ' ']) && $state == static::STATE_OPEN) {
                $state = static::STATE_PARAMETER;
            }
            if (in_array($state, [static::STATE_OPEN, static::STATE_PARAMETER])) {
                if ($i > 0 && $text[$i - 1] == ']') {
                    $state = static::STATE_CONTENT;
                }
            }
            if (preg_match('/[a-z0-9]/i', $char)) {
                if ($state == static::STATE_OPEN) {
                    $openTag .= $char;
                } elseif ($state == static::STATE_CLOSE) {
                    $closeTag .= $char;
                }
            }
            if ($state != static::STATE_STOP) {
                $block .= $char;
            } else {
                $newText .= $char;
            }
            if ($char == '[' && isset($text[$i + 1]) && $text[$i + 1] == '/') {
                $state = static::STATE_CLOSE;
            }
            if ($char == ']' && $state == static::STATE_CLOSE) {
                if ($openTag === $closeTag) {
                    $state = static::STATE_STOP;
                    $newText .= $this->parseBlock($openTag, $block);
                    $block = $openTag = $closeTag = '';
                } else {
                    $state = static::STATE_CONTENT;
                    $closeTag = '';
                }
            }
        }

        return $newText;
    }

    /**
     * @param string $tagName
     * @param string $block
     * @return string
     */
    protected function parseBlock($tagName, $block)
    {
        $tag = new Tag($tagName);
        $text = $tag->render($block);

        if ($tag->shouldRender()) {
            return $this->parse($text);
        }

        return $text;
    }
}
