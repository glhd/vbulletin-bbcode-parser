<?php

namespace Galahad\Bbcode;

use Galahad\Bbcode\Exception\MissingAttributeException;
use Galahad\Bbcode\Exception\MissingTagException;

/**
 * Class Tag
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Tag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $content;

    /**
     * @var mixed
     */
    protected $attribute;

    /**
     * @param string $name
     * @param mixed $content
     * @param mixed|null $attribute
     */
    public function __construct($name, $content, $attribute = null)
    {
        $this->name = $name;
        $this->content = $content;
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    public function tagColor()
    {
        $this->validateAttribute();

        return sprintf(
            '<span style="color: %s;">%s</span>',
            $this->attribute,
            $this->content
        );
    }

    /**
     * @return string
     * @see http://style.cleverchimp.com/font_size_intervals/altintervals.html#bbs
     */
    public function tagSize()
    {
        $this->validateAttribute();

        $scale = [60, 89, 100, 120, 150, 200, 300];
        $position = intval($this->attribute) - 1; // -1 -1 or +1 - 1

        if (in_array($this->attribute[0], ['-', '+'])) {
            $position = 3 + $position;
        }

        return sprintf(
            '<span style="font-size: %s;">%s</span>',
            isset($scale[$position]) ? $scale[$position] . '%' : $this->attribute,
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagFont()
    {
        $this->validateAttribute();

        return sprintf(
            '<span style="font-family: %s;">%s</span>',
            $this->attribute,
            $this->content
        );
    }

    /**
     * @return string
     */
    public function tagHighlight()
    {
        return sprintf(
            '<mark>%s</mark>',
            $this->content
        );
    }

    /**
     * @throws MissingAttributeException
     */
    protected function validateAttribute()
    {
        if (empty(trim($this->attribute))) {
            throw new MissingAttributeException();
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function render()
    {
        $method = 'tag'.ucfirst($this->name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new MissingTagException("Missing parser for $this->name tag");
    }
}
