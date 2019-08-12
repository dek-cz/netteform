<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\TextInput as BaseInput;
use Nette\Utils\Html;

class TextInput extends BaseInput
{

    use TWrapp;

    public function getControl($wrap = true): Html
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return Html::el()->setHtml($this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this));
        }
        return parent::getControl();
    }

}
