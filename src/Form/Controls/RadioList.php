<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\ChoiceControl as BaseChControl;

class RadioList extends BaseChControl
{

    private $mainLabel = null;

    public function __construct($label = NULL, array $items = NULL)
    {
        $this->mainLabel = $label;
        parent::__construct(null, $items);
    }

    public function getControl()
    {
        $label = '';
        if ($this->mainLabel) {
            $label = $this->getLabel($this->mainLabel);
        }
        $res = $label;
        foreach ($this->getItems() as $item) {
            $res .= $item->getControl();
        }

        return $res;
    }

    public function getLabel($caption = NULL)
    {
        return $caption ? \Nette\Utils\Html::el('div')->setClass('dek-choice-list')->setText($this->translate($caption)) : '';
    }

}
