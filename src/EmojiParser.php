<?php

namespace Galahad\Bbcode;

/**
 * Class EmojiParser
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class EmojiParser
{
    /**
     * @param string $content
     * @return string
     */
    protected function defaultTranslations($content)
    {
        $translations = [
            ':mrgreen:' => ':grinning:',
            ':sarcasm:' => ':upside_down:',
            '\\\:D/' => ':stuck_out_tongue:',
            ':cool-cat:' => ':smile_cat:',
            ':D' => ':grinning:',
            ':)' => ':slight_smile:',
        ];

        return str_replace(
            array_keys($translations), array_values($translations), $content
        );
    }
}
