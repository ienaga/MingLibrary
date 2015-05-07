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
     * @param int    $width
     * @param int    $height
     * @param string $backgroundColor
     * @param int    $rate
     * @param int    $version
     * @return SWFMovie
     */
    public function init($width = 240, $height = 240, $backgroundColor = '#000000', $rate = 12, $version = 4)
    {
        ming_useswfversion($version);
        ming_setscale(20);

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

        return $swf;
    }

    /**
     * @return SWFMovie
     */
    public function getSwf()
    {
        return $this->swf;
    }

    /**
     * @param  SWFMovie|SWFSprite
     * @param  array $clips
     * @return SWFMovie|SWFSprite
     */
    public function build($swf, $clips = array())
    {
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
                        $SWFDisplayItem->addAction(new SWFAction($action));
                    }
                }
            }

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

    /**
     * @param SWFMovie $swf
     * @param string   $path
     */
    public function save(SWFMovie $swf, $path)
    {
        $swf->save($path);
    }
}