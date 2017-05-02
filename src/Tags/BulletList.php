<?php

namespace Galahad\Bbcode\Tags;

use Illuminate\Support\Arr;

/**
 * Class BulletList
 *
 * @package Galahad\Bbcode\Tags
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class BulletList implements TagInterface
{
    /**
     * @var string
     */
    protected $pattern = '/(?:\[\*\]\s*(.*))+/i';

    /**
     * @var string
     */
    protected $html = '<ul><li>%s</li></ul>';

    /**
     * @param string $text
     * @return string
     */
    public function render($text)
    {
        preg_match_all($this->pattern, $text, $matches);
        $items = Arr::get($matches, 1);
        $items = implode('</li><li>', $items);

        return sprintf($this->html, $items);
    }
}
