<?php

namespace Galahad\Bbcode\Tags;

/**
 * Class AdvancedList
 *
 * @package Galahad\Bbcode\Tags
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class AdvancedList extends BulletList
{
    /**
     * @var mixed
     */
    protected $attribute;

    /**
     * @var string
     */
    protected $html = '<ol type=":attribute"><li>%s</li></ol>';

    /**
     * @param mixed $attribute
     */
    public function __construct($attribute)
    {
        $this->attribute = $attribute;
        $this->html = str_replace(':attribute', $attribute, $this->html);
    }
}
