<?php

namespace Galahad\Bbcode\Tags;

/**
 * Interface TagInterface
 *
 * @package Galahad\Bbcode\Tags
 * @author Junior Grossi <juniorgro@gmail.com>
 */
interface CustomTagInterface
{
    /**
     * @param string $block
     * @param array $attributes
     * @param string $content
     * @return string
     */
    public function render($block, array $attributes, $content);
}
