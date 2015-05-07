<?php

namespace MingLibrary\App;

use MingLibrary\MingBase;

class Sample extends MingBase
{
    /**
     * @param int    $width
     * @param int    $height
     * @param string $backgroundColor
     * @param int    $rate
     * @param int    $version
     */
    public function __constructor($width = 240, $height = 240, $backgroundColor = '#000000', $rate = 12, $version = 4)
    {
        parent::__constructor($width, $height, $backgroundColor, $rate, $version);
    }
}

