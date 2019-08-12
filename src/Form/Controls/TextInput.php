<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\TextInput as BaseInput;

class TextInput extends BaseInput
{

    use TWrapp;

    public function getControl($wrap = true)
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this);
        }
        return parent::getControl();
    }

}
