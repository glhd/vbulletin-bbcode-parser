<?php

namespace Galahad\Bbcode\Tags;

/**
 * Interface TagInterface
 *
 * @package Galahad\Bbcode\Tags
 * @author Junior Grossi <juniorgro@gmail.com>
 */
interface TagInterface
{
    /**
     * @param string $text
     * @return string
     */
    public function render($text);
}