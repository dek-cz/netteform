<?php

namespace DekApps\Form\Controls;

use \Nette\Forms\Controls\Checkbox as BaseCheckBox;

class Checkbox extends BaseCheckBox
{

    use TWrapp;

    const TITLE = 'title';
    const TEXT = 'text';

    /** @var string */
    protected $text;

    /** @var bool */
    protected $readonly = '';

    /**
     * 'title'=>[]
     * 'text'=>[]
     * 
     * @var []
     */
    protected $textParams = [];

    public function __construct($label = NULL)
    {
        parent::__construct($label);
        $this->setAttribute('role', 'checkbox');
    }

    public function setText($text)
    {
        if (!is_string($text)) {
            throw new \Exception(sprintf("Value must be STRING, %s given in field '%s'.", gettype($text), $this->name));
        }
        $this->text = $text;
        return $this;
    }

    public function setReadOnly()
    {
        $this->readonly = 'readonly';
        $this->setAttribute('readonly', 'readonly');
        $this->setAttribute('onclick', 'return false;');
        return $this;
    }

    public function getReadonly()
    {
        return $this->readonly;
    }

    /**
     * Returns control's value.
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    public function getControl($wrap = true)
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderControl($this);
        }
        $label = $this->getLabelPart();
        $label->setText('');
        $label->addClass('dek-checkbox');
        $label->insert(0, $this->getControlPart());
        $label->insert(1, \Nette\Utils\Html::el('span')->setClass('dek-checkbox__check'));
        $label->insert(2, \Nette\Utils\Html::el('span')->setClass('dek-checkbox__label')->setText($this->translate($this->caption), isset($this->textParams[self::TITLE]) ? $this->textParams[self::TITLE] : []));
        if ($this->text) {
            $label->insert(3, \Nette\Utils\Html::el('span')->setClass('dek-checkbox__text')->setText($this->translate($this->text), isset($this->textParams[self::TEXT]) ? $this->textParams[self::TEXT] : []));
        }

        return $this->getSeparatorPrototype()->setHtml($label);
    }

    public function setTitleParams($params)
    {

        $this->textParams[self::TITLE] = $params;
        return $this;
    }

    public function setTextParams($params)
    {

        $this->textParams[self::TEXT] = $params;
        return $this;
    }

}
