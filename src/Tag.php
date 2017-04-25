<?php

namespace Galahad\Bbcode;

/**
 * Class Tag
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 */
abstract class Tag
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
    abstract public function build();
}
