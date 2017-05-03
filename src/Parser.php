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
    const STATE_STARTED = 0;
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
        $state = static::STATE_STARTED;
        $block = $newText = $openTag = $closeTag = $content = '';

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            if ($char == '[' && $state == static::STATE_STARTED) {
                $state = static::STATE_OPEN;
            }
            if (in_array($char, ['=', ' ']) && $state == static::STATE_OPEN) {
                $state = static::STATE_PARAMETER;
            }
            if (in_array($state, [static::STATE_OPEN, static::STATE_PARAMETER]) && $text[$i - 1] == ']') {
                $state = static::STATE_CONTENT;
            }
            if (preg_match('/[a-z0-9]/i', $char)) {
                if ($state == static::STATE_OPEN) {
                    $openTag .= $char;
                } elseif ($state == static::STATE_CLOSE) {
                    $closeTag .= $char;
                }
            }
            if ($state != static::STATE_STARTED) {
                $block .= $char;
            } else {
                $newText .= $char;
            }
            if ($char == '[' && $text[$i + 1] == '/') {
                $state = static::STATE_CLOSE;
            }
            if ($char == ']' && $state == static::STATE_CLOSE) {
                if ($openTag === $closeTag) {
                    $state = static::STATE_STARTED;

                    $tag = new Tag($openTag);
                    $html = $tag->render($block);

                    $newText .= $html;

                    $block = $openTag = $closeTag = $content = '';
                } else {
                    $state = static::STATE_CONTENT;
                    $closeTag = '';
                }
            } elseif ($state === static::STATE_CONTENT) {
                $content .= $char;
            }
        }

        return $newText;
    }
}
