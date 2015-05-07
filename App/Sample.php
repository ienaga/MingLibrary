<?php

namespace MingLibrary\App;


class Sample extends \MingLibrary\MingBase
{
    /**
     * @param int    $width
     * @param int    $height
     * @param string $backgroundColor
     * @param int    $rate
     * @param int    $version
     */
    public function __construct($width = 240, $height = 240, $backgroundColor = '#000000', $rate = 12, $version = 4)
    {
        parent::__construct($width, $height, $backgroundColor, $rate, $version);
    }


}

