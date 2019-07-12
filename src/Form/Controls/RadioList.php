<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\ChoiceControl as BaseChControl;

class RadioList extends BaseChControl
{

    use TWrapp;

    private $mainLabel = null;

    public function __construct($label = NULL, array $items = NULL)
    {
        $this->mainLabel = $label;
        parent::__construct(null, $items);
    }

    public function getControl($wrapp = true)
    {
        if ($this->getDekWrapper() && $wrapp && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderControl($this);
        }

        $res = '';
        foreach ($this->getItems() as $item) {
            $res .= $item->getControl();
        }

        return $res;
    }

    public function getLabel($caption = NULL)
    {
        return \Nette\Utils\Html::el('div')->setClass('dek-radio-list-label')->setText($this->translate($caption === null ? $this->mainLabel : $caption));
    }

    public function setValue($value)
    {
        foreach ($this->getItems() as $item) {
            if ($item->getValue() == $value) {
                $item->setChecked(true);
                break;
            }
        }
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setRequired($value = TRUE)
    {
        foreach ($this->getItems() as $item) {
            $item->setRequired($value);
        }
        return $this;
    }

    /**
     * Returns selected value.
     * @return mixed
     */
    public function getSelectedItem()
    {
        $value = $this->getValue();
        foreach ($this->getItems() as $item) {
            if ($item->getValue() === $value) {
                return $item;
            }
        }
        return null;
    }

    public function loadHttpData()
    {
        $val = $this->getHttpData(\Nette\Forms\Form::DATA_LINE);
        $this->setValue($val);
    }

}
