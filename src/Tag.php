<?php

namespace Galahad\Bbcode;

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
        return sprintf(
            '<span style="color: %s;">%s</span>',
            $this->attribute,
            $this->content
        );
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function toHtml()
    {
        $method = 'tag'.ucfirst($this->name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new \Exception("Missing parser for $this->name tag");
    }
}
