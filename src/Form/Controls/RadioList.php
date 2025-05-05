<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\ChoiceControl as BaseChControl;
use Nette\Utils\Html;
use Stringable;

class RadioList extends BaseChControl
{

    use TWrapp;

    private $mainLabel = null;

    public function __construct($label = NULL, array $items = NULL)
    {
        $this->mainLabel = $label;
        parent::__construct(null, $items);
    }

    public function getControl($wrap = true): string
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderControl($this);
        }

        $res = '';
        foreach ($this->getItems() as $item) {
            $res .= $item->getControl();
        }

        return $res;
    }

    public function getLabel(Stringable|string|null $caption = NULL): Html
    {
        return Html::el('div')->setClass('dek-radio-list-label')->setText($this->translate($caption === null ? $this->mainLabel : $caption));
    }

    public function setValue($value): static
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

    /**
     * overide
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setRequired(Stringable|string|bool $value = true): static
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
    public function getSelectedItem(): mixed
    {
        $value = $this->getValue();
        foreach ($this->getItems() as $item) {
            if ($item->getValue() === $value) {
                return $item;
            }
        }
        return null;
    }

    public function loadHttpData(): void
    {
        $val = $this->getHttpData(\Nette\Forms\Form::DATA_LINE);
        $this->setValue($val);
    }

}
