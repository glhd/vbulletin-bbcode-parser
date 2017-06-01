<?php

namespace Galahad\Bbcode\Exception;

/**
 * Class MissingTagException
 *
 * @package Galahad\Bbcode\Exception
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class MissingTagException extends \Exception
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct("Missing parser for $name tag");
    }
}
