<?php

namespace MingLibrary;

use \SWFBitmap;
use \SWFShape;
use \SWFSprite;
use \MingLibrary\MingUtil;

class MingBitmap
{

    /**
     * @var string
     */
    private $path = '';

    /**
     * @param string $path
     */
    public function __constructor($path)
    {
        $this->setPath($path);
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return SWFSprite
     */
    public function build()
    {
        $shape  = new SWFShape();
        $bitmap = new SWFBitmap(fopen(MingUtil::getBitmapDir() . $this->getPath(), 'r'));
        $sprite = new SWFSprite();

        // draw
        $shape->setRightFill($shape->addFill($bitmap));
        $shape->movePenTo(0, 0);
        $shape->drawLineTo($bitmap->getWidth(), 0);
        $shape->drawLineTo($bitmap->getWidth(), $bitmap->getHeight());
        $shape->drawLineTo(0, $bitmap->getHeight());
        $shape->drawLineTo(0, 0);

        // set
        $sprite->add($shape);
        $sprite->nextFrame();

        return $sprite;
    }

}