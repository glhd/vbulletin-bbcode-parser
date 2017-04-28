<?php

namespace Galahad\Bbcode;

use Illuminate\Support\ServiceProvider;

/**
 * Class BbcodeServiceProvider
 *
 * @package Galahad\Bbcode
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class BbcodeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/vbulletin.php' => config_path('vbulletin.php'),
        ]);
    }
}