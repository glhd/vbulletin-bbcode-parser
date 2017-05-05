<?php

namespace Galahad\Bbcode\Tags;

use Illuminate\Support\Arr;

/**
 * Class ListTag
 *
 * @package Galahad\Bbcode\Tags
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ListTag implements TagInterface
{
    /**
     * @var string
     */
    protected $pattern = '/(?:\[\*\]\s*(.*))+/i';

    /**
     * @var mixed
     */
    protected $attribute;

    /**
     * @var array
     */
    protected $html = [
        'unordered' => '<ul><li>%s</li></ul>',
        'ordered' => '<ol type=":attribute"><li>%s</li></ol>',
    ];

    /**
     * @param mixed $attribute
     */
    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @param string $text
     * @return string
     */
    public function render($text)
    {
        preg_match_all($this->pattern, $text, $matches);
        $items = Arr::get($matches, 1);
        $items = implode('</li><li>', $items);

        return sprintf($this->html(), $items);
    }

    /**
     * @return string
     */
    protected function html()
    {
        if ($this->attribute) {
            $html = Arr::get($this->html, 'ordered');

            return str_replace(':attribute', $this->attribute, $html);
        }

        return Arr::get($this->html, 'unordered');
    }
}
