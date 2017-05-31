<?php

namespace Galahad\Bbcode;

use Emojione\Client;

/**
 * Class EmojiParser
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class EmojiParser
{
    const EMOJI_PATH = 'https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/assets';

    /**
     * @var Client
     */
    protected $emojione;

    public function __construct()
    {
        $this->emojione = $this->createEmojione();
    }

    /**
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        $text = $this->defaultTranslations($text);

        return $this->emojione->shortnameToImage($text);
    }

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

    /**
     * @return Client
     */
    private function createEmojione()
    {
        $client =  new Client(new Ruleset());
        $client->ascii = true;
        $client->imagePathPNG = static::EMOJI_PATH;

        return $client;
    }
}
