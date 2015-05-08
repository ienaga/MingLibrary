<?php

namespace MingLibrary;

use \SWFMovie;
use \MingLibrary\MingUtil;
use \MingLibrary\MingBuild;
use \Exception;

class MingBase
{
    /**
     * @var int
     */
    private $width = 240;

    /**
     * @var int
     */
    private $height = 240;

    /**
     * @var int
     */
    private $backgroundColor = '#000000';

    /**
     * @var int
     */
    private $rate = 12;

    /**
     * @var int
     */
    private $version = 4;

    /**
     * @var int フレーム数
     */
    private $frameCount = 0;

    /**
     * @var array
     */
    private $clips = array();

    /**
     * @var MingBuild
     */
    private $mingBuild = null;


    /**
     * @param int    $width
     * @param int    $height
     * @param string $backgroundColor
     * @param int    $rate
     * @param int    $version
     */
    public function __construct($width = 240, $height = 240, $backgroundColor = '#000000', $rate = 12, $version = 4)
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setBackgroundColor($backgroundColor);
        $this->setRate($rate);
        $this->setVersion($version);
        $this->clips[0] = array();
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width = 240)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height = 240)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     */
    public function setBackgroundColor($backgroundColor = '#000000')
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int $rate
     */
    public function setRate($rate = 12)
    {
        $this->rate = $rate;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion($version = 4)
    {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getFrameCount()
    {
        return $this->frameCount;
    }

    /**
     * @param $frameCount
     */
    public function setFrameCount($frameCount)
    {
        $this->frameCount = $frameCount;
    }

    /**
     * @return array
     */
    public function getClips()
    {
        return $this->clips[$this->getFrameCount()];
    }

    /**
     * @return array
     */
    public function getBuildClips()
    {
        return $this->clips;
    }

    /**.
     * @param null  $name
     * @param mixed $obj
     * @param int   $x
     * @param int   $y
     * @param float $xScale
     * @param float $yScale
     * @param int   $alpha
     * @param int   $angle
     * @param array $actions
     */
    public function setClips($name = null, $obj, $x = 0, $y = 0, $xScale = 1.0, $yScale = 1.0, $alpha = 100, $angle = 0, $actions = array())
    {
        $clips = $this->getClips();

        $arg = array(
            'obj' => $obj,
            'x' => $x,
            'y' => $y,
            'xScale' => $xScale,
            'yScale' => $yScale,
            'alpha' => $alpha,
            'angle' => $angle,
            'actions' => $actions,
        );

        if (!$name)
            $name = count($clips);

        $this->clips[$this->getFrameCount()][$name] = $arg;
    }

    /**
     * @param null   $name
     * @param string $path
     * @param int    $x
     * @param int    $y
     * @param float  $xScale
     * @param float  $yScale
     * @param int    $alpha
     * @param int    $angle
     * @param array  $actions
     */
    public function add($name = null, $path = '', $x = 0, $y = 0, $xScale = 1.0, $yScale = 1.0, $alpha = 100, $angle = 0, $actions = array())
    {
        $this->setClips($name, $path, $x, $y, $xScale, $yScale, $alpha, $angle, $actions);
    }

    /**
     * @param  string $name
     * @param  string $action
     * @throws Exception
     */
    public function addAction($name, $action)
    {
        $clips = $this->getClips();

        if (!isset($clips[$name]))
            throw new Exception('not found clip name is: '.$name);

        $clip = $clips[$name];
        $actions = $clip['actions'];

        $actions[] = $action;

        $clip['actions'] = $actions;

        $this->clips[$this->getFrameCount()][$name] = $clip;
    }

    /**
     * @param null   $name
     * @param string $path
     * @param int    $x
     * @param int    $y
     * @param float  $xScale
     * @param float  $yScale
     * @param int    $alpha
     * @param int    $angle
     * @param array  $actions
     */
    public function addBitmap($name = null, $path = '', $x = 0, $y = 0, $xScale = 1.0, $yScale = 1.0, $alpha = 100, $angle = 0, $actions = array())
    {
        $this->add($name, new MingBitmap($path), $x, $y, $xScale, $yScale, $alpha, $angle, $actions);
    }

    /**
     * @param string $name
     * @param int    $x
     * @param int    $y
     * @param float  $xScale
     * @param float  $yScale
     * @param int    $alpha
     * @param int    $angle
     * @param array  $actions
     */
    public function createSprite($name, $x = 0, $y = 0, $xScale = 1.0, $yScale = 1.0, $alpha = 100, $angle = 0, $actions = array())
    {
        $this->add($name, new MingSprite(), $x, $y, $xScale, $yScale, $alpha, $angle, $actions);
    }

    /**
     * @param null   $name
     * @param string $path
     * @param int    $x
     * @param int    $y
     * @param float  $xScale
     * @param float  $yScale
     * @param int    $alpha
     * @param int    $angle
     * @param array  $actions
     */
    public function addSprite($name = null, $path = '', $x = 0, $y = 0, $xScale = 1.0, $yScale = 1.0, $alpha = 100, $angle = 0, $actions = array())
    {
        $clips = $this->getClips();

        $mingSprite = $clips[$name];
        if (!$mingSprite)
            throw new Exception('not found sprite name is: '.$name);

        $mingSprite->add($name, $path, $x, $y, $xScale, $yScale, $alpha, $angle, $actions);

        $this->clips[$this->getFrameCount()][$name] = $mingSprite;
    }

    /**
     * @return MingBuild
     */
    public function getMingBuild()
    {
        return $this->mingBuild;
    }

    /**
     * @param MingBuild $mingBuild
     */
    public function setMingBuild(MingBuild $mingBuild)
    {
        $this->mingBuild = $mingBuild;
    }

    /**
     * @return SWFMovie
     */
    public function build()
    {
        $mingBuild = new MingBuild();

        $swfMovie = $mingBuild->init($this->getWidth(), $this->getHeight(), $this->getBackgroundColor(), $this->getRate(), $this->getVersion());

        $swf =  $mingBuild->build($swfMovie, $this->getBuildClips());

        $this->setMingBuild($mingBuild);

        return $swf;
    }

    /**
     * @param SWFMovie $swf
     */
    public function output(SWFMovie $swf)
    {
        $this->getMingBuild()->output($swf);
    }
}