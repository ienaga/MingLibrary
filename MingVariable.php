<?php

namespace MingLibrary;

use \SWFSprite;
use \SWFAction;
use \MingLibrary\MingUtil;

class MingVariable
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var array
     */
    private $variables = array();

    /**
     * @param string $name
     */
    public function __construct($name = '')
    {
        $this->name = $name;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function add($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * @return SWFSprite
     */
    public function build()
    {
        $script = 'stop();';
        foreach ($this->variables as $key => $value) {
            $script .= $key.' = "'. MingUtil::encoding_to_sjis($value) .'";';
        }

        $sprite = new SWFSprite();
        $sprite->add(new SWFAction($script));
        $sprite->nextFrame();

        return $sprite;
    }
}