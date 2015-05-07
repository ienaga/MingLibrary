<?php

namespace MingLibrary;

use \SWFSprite;

class MingSprite extends MingBase
{
    /**
     * @return SWFSprite
     */
    public function build()
    {
        $mingBuild = new MingBuild();

        return $mingBuild->build(new SWFSprite(), $this->getClips());
    }
}