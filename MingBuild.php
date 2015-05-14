<?php

namespace MingLibrary;

use \SWFMovie;
use \SWFPrebuiltClip;
use \SWFAction;
use \SWFSprite;
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
     * @param  SWFMovie|SWFSprite
     * @param  array $clips
     * @return SWFMovie|SWFSprite
     */
    public function build($clips = array())
    {
        $frameCount = count($clips);

        $swf = $this->getSwf();
        for ($frame = 0; $frame < $frameCount; $frame++) {

            $swf->add(new SWFAction("stop();"));

            $clip = $clips[$frame];
            foreach ($clip as $name => $value) {

                // action script
                $script = '';
                if (count($value['actions']) > 0) {
                    foreach ($value['actions'] as $action) {
                        $script .= $action;
                    }
                }

                $obj = $value['obj'];

                if ($obj instanceof MingSprite
                    || $obj instanceof MingBitmap
                    || $obj instanceof MingVariable
                ) {

                    $addClip = $obj->build();

                    if ($script != '') {
                        $addClip->add(new SWFAction($script));
                    }

                    $script = '';

                } else if ($obj != '') {
                    $addClip = new SWFPrebuiltClip(MingUtil::getSwfDir() . $obj);
                } else {
                    continue;
                }

                $SWFDisplayItem = $swf->add($addClip);

                // name
                if (is_string($name))
                    $SWFDisplayItem->setName($name);

                // move
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

            }

            if ($script != '') {
                $swf->add(new SWFAction($script));
            }

            $swf->nextFrame();
        }

        $this->swf = $swf;
    }

    /**
     * output
     */
    public function output()
    {
        MingUtil::setHeader();

        $this->getSwf()->output();

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