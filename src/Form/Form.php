<?php

namespace DekApps\Form;

use Nette\Application\UI\Form as BaseForm;
use DekApps\Form\Controls\Checkbox;
use DekApps\Form\Controls\RadioList;
use DekApps\Form\Controls\DateTimeInput;
use DekApps\Form\Controls\PhoneInput;
use DekApps\Form\Controls\TextInput;
use Nette\ComponentModel\IContainer;
use Nette\Forms\IFormRenderer;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;

class Form extends BaseForm
{

    public function __construct($container = NULL)
    {
        parent::__construct($container);
        $this->setRenderer(new Renderer);
        $this->getElementPrototype()->setRole('form');
    }

    public function addSearchText($name, $caption = NULL, $cols = NULL, $maxLength = NULL)
    {
        return $this->addText($name, $caption, $cols = NULL, $maxLength = NULL)->setAttribute('role', 'textbox')->setAttribute('type', 'search');
    }

    public function addEmailText($name, $caption = NULL, $cols = NULL, $maxLength = NULL)
    {
        return $this->addText($name, $caption, $cols = NULL, $maxLength = NULL)->setAttribute('role', 'textbox')->setAttribute('type', 'email');
    }

    public function addText($name, $caption = NULL, $cols = NULL, $maxLength = NULL)
    {
		$control = new TextInput($caption, $maxLength);
		$control->setHtmlAttribute('size', $cols);
		return $this[$name] = $control->setAttribute('role', 'textbox');
    }

    public function addCheckbox($name, $caption = NULL)
    {
        return ($this[$name] = (new Checkbox($caption)));
    }

    public function addCalendar($name, $caption = NULL)
    {
        return ($this[$name] = (new Calendar($caption))->setAttribute('role', 'textbox'));
    }

    public function addDateTimeInput($name, $caption = NULL, $type = DateTimeInput::TYPE_DATETIME_NOSEC)
    {
        return ($this[$name] = (new DateTimeInput($caption, $type))->setAttribute('role', 'textbox'));
    }

    public function addPhoneInput($name, $caption = NULL)
    {
        return ($this[$name] = (new PhoneInput($caption))->setAttribute('role', 'textbox'));
    }

    public function addButton($name, $caption = NULL)
    {
        return parent::addButton($name, $caption)->setAttribute('role', 'button');
    }

    public function addSelect($name, $label = NULL, array $items = NULL, $size = NULL)
    {
        return parent::addSelect($name, $label, $items, $size)->setAttribute('role', 'combobox');
    }

    public function addSubmit($name, $caption = NULL)
    {
        return parent::addSubmit($name, $caption)->setAttribute('role', 'button');
    }

    public function addTextArea($name, $label = NULL, $cols = NULL, $rows = NULL)
    {
        return parent::addTextArea($name, $label, $cols, $rows)->setAttribute('role', 'dek-textarea');
    }

    public function addUpload($name, $label = NULL, $multiple = FALSE)
    {
        return parent::addUpload($name, $label, $multiple)->setAttribute('role', 'dek-upload');
    }

    /**
     * 
     * @param string $caption
     * @param [] $items
     * @return RadioList
     */
    public function addRadios($caption = NULL, $items)
    {
        foreach ($items as $i) {
            $i->setForm($this);
        }
        $fitem = reset($items);
        return ($this[$fitem->getRadioName()] = (new RadioList($caption, $items)));
    }

}
