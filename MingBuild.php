<?php

namespace MingLibrary;

use \SWFMovie;
use \SWFPrebuiltClip;
use \SWFAction;
use \MingLibrary\MingUtil;
use \MingLibrary\MingBitmap;
use \MingLibrary\MingSprite;

class MingBuild
{
    /**
     * @var SWFMovie
     */
    private $swf = null;

    /**
     * @param int    $width
     * @param int    $height
     * @param string $backgroundColor
     * @param int    $rate
     * @param int    $version
     */
    public function __constructor($width = 240, $height = 240, $backgroundColor = '#000000', $rate = 12, $version = 4)
    {
        // init
        ming_useswfversion($version);
        ming_setscale(20);

        // set
        $swf = new SWFMovie($version);
        $swf->setDimension($width, $height);
        $swf->setRate($rate);
        $swf->setbackground(
            hexdec(substr($backgroundColor,1 ,2)),
            hexdec(substr($backgroundColor,3 ,2)),
            hexdec(substr($backgroundColor,5 ,2))
        );

        // focus
        $swf->add(new SWFAction('_focusrect = false;'));

        $this->swf = $swf;
    }

    /**
     * @return SWFMovie
     */
    public function getSwf()
    {
        return $this->swf;
    }

    /**
     * @param  array $clips
     * @return SWFMovie
     */
    public function build($clips = array())
    {

        $swf = $this->getSwf();

        $frameCount = count($clips);
        for ($frame = 0; $frame <= $frameCount; $frame++) {

            $swf->add(new SWFAction("stop();"));

            $clip = $clips[$frame];
            foreach ($clip as $name => $value) {

                $obj = $value['obj'];

                if ($obj instanceof MingSprite || $obj instanceof MingBitmap) {
                    $addClip = $obj->build();
                } else {
                    $addClip = new SWFPrebuiltClip(MingUtil::getSwfDir() . $obj);
                }

                $SWFDisplayItem = $swf->add($addClip);

                // name
                if (is_string($name))
                    $SWFDisplayItem->setName($name);

                // move
                if ($value['x'] != 0 || $value['y'] != 0)
                    $SWFDisplayItem->moveTo($value['x'], $value['y']);

                // scale
                if ($value['xScale'] != 1.0 || $value['yScale'] !=  1.0)
                    $SWFDisplayItem->scaleTo($value['xScale'], $value['yScale']);

                // rotate
                if ($value['angle'] != 0)
                    $SWFDisplayItem->rotateTo($value['angle']);

                // alpha
                if ($value['alpha'] < 100)
                    $SWFDisplayItem->multColor(1, 1, 1, $value['alpha'] / 100);

                // action script
                if (count($value['actions']) > 0) {
                    foreach ($value['actions'] as $action) {
                        $obj->addAction(new SWFAction($action));
                    }
                }
            }

            if ($frame)
                $swf->nextFrame();

        }

        return $swf;
    }

    /**
     * @param SWFMovie $swf
     */
    public function output(SWFMovie $swf)
    {

        MingUtil::setHeader();

        $swf->output();

        exit;
    }

}