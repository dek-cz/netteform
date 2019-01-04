<?php

namespace DekApps\Form\Controls;

trait TWrapp
{

    private $dekWrapper = true;

    public function getDekWrapper()
    {
        return $this->dekWrapper;
    }

    public function setDekWrapper($dekWrapper)
    {
        $this->dekWrapper = $dekWrapper;
        return $this;
    }


}
