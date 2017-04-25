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
    protected $tag;

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
     * @param string $tag
     * @param string $name
     * @param mixed $content
     * @param mixed|null $attribute
     */
    public function __construct($tag, $name, $content, $attribute = null)
    {
        $this->tag = $tag;
        $this->name = $name;
        $this->content = $content;
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getAttribute()
    {
        return $this->attribute;
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
