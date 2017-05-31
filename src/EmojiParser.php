<?php

namespace Galahad\Bbcode;

use Emojione\Client;
use Emojione\Ruleset;

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

    /**
     * @var bool
     */
    protected $useUnicode = true;

    public function __construct()
    {
        $this->emojione = $this->createEmojione();
    }

    /**
     * @param bool $activate
     * @return $this
     */
    public function unicode($activate = true)
    {
        $this->useUnicode = $activate;

        return $this;
    }

    /**
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        $text = $this->defaultTranslations($text);

        if ($this->useUnicode) {
            return $this->emojione->unifyUnicode($text);
        }

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
            ':neutral:' => ':neutral_face:',
            '\\\:D/' => ':stuck_out_tongue:',
            ':cool-cat:' => ':smile_cat:',
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
        $client->ascii = true; // allow :D ;-) =) etc
        $client->riskyMatchAscii = false; // space required between ASCII
        $client->imagePathPNG = static::EMOJI_PATH;

        return $client;
    }
}
