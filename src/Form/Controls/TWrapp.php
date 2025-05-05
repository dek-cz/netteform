<?php

namespace DekApps\Form\Controls;

trait TWrapp
{

    private $dekWrapper = true;

    public function getDekWrapper(): bool
    {
        return $this->dekWrapper;
    }

    public function setDekWrapper(bool $dekWrapper): static
    {
        $this->dekWrapper = $dekWrapper;
        return $this;
    }


}
