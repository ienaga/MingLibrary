<?php

namespace MingLibrary;

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
     * @param int    $width
     * @param int    $height
     * @param string $backgroundColor
     * @param int    $rate
     * @param int    $version
     */
    public function __constructor($width = 240, $height = 240, $backgroundColor = '#000000', $rate = 12, $version = 4)
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setBackgroundColor($backgroundColor);
        $this->setRate($rate);
        $this->setVersion($version);
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
        return $this->clips;
    }

    /**.
     * @param null  $name
     * @param mixed $obj
     * @param float $x
     * @param float $y
     * @param float $xScale
     * @param float $yScale
     * @param int   $alpha
     * @param int   $angle
     * @param array $actions
     */
    public function setClips($name = null, $obj, $x = 0.0, $y = 0.0, $xScale = 1.0, $yScale = 1.0, $alpha = 0, $angle = 0, $actions = array())
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

        if ($name)
            $name = count($clips[$this->getFrameCount()]);

        $clips[$this->getFrameCount()][$name] = $arg;
    }

    /**
     * @param null   $name
     * @param string $path
     * @param float  $x
     * @param float  $y
     * @param float  $xScale
     * @param float  $yScale
     * @param int    $alpha
     * @param int    $angle
     * @param array  $actions
     */
    public function add($name = null, $path = '', $x = 0.0, $y = 0.0, $xScale = 1.0, $yScale = 1.0, $alpha = 0, $angle = 100, $actions = array())
    {
        $this->setClips(func_get_args());
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
            throw new Exception('not found clip');

        $actions = $clips[$name]['actions'];

        $actions[] = $action;
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

        if (!isset($clips[$name]))
            $mingSprite = new MingSprite();

        $mingSprite->add(func_get_args());

        $clips[$name] = $mingSprite;
    }

    /**
     * @return \SWFMovie
     */
    public function build()
    {
        $mingBuild = new MingBuild($this->getWidth(), $this->getHeight(), $this->getBackgroundColor(), $this->getRate(), $this->getVersion());

        return $mingBuild->build($this->getClips());
    }

    /**
     * @param MingBuild $mingBuild
     */
    public function output(MingBuild $mingBuild)
    {
        $mingBuild->output($mingBuild);
    }

}