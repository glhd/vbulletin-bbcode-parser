<?php

namespace Galahad\Bbcode\Tests;

use Galahad\Bbcode\Tags\CustomTagInterface;

/**
 * Class FooTag
 *
 * @package Galahad\Bbcode\Tests
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class FooTag implements CustomTagInterface
{
    /**
     * @param string $block
     * @param array $attributes
     * @param string $content
     * @return string
     */
    public function render($block, array $attributes, $content)
    {
        return '<a href="#foo">'.$content.'</a>';
    }
}
