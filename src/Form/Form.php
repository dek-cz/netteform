<?php

namespace DekApps\Form;

use Nette\Application\UI\Form as BaseForm;
use DekApps\Form\Controls\Checkbox;
use DekApps\Form\Controls\RadioList;
use DekApps\Form\Controls\DateTimeInput;
use DekApps\Form\Controls\Calendar;
use DekApps\Form\Controls\PhoneInput;
use DekApps\Form\Controls\TextInput;
use Nette\ComponentModel\IContainer;

class Form extends BaseForm
{

    public function __construct(IContainer $container = NULL)
    {
        parent::__construct($container);
        $this->setRenderer(new Renderer);
        $this->getElementPrototype()->setRole('form');
    }

    public function addSearchText($name, $caption = NULL, $cols = NULL, $maxLength = NULL): \Nette\Forms\Controls\TextInput
    {
        return $this->addText($name, $caption, $cols = NULL, $maxLength = NULL)->setHtmlAttribute('role', 'textbox')->setHtmlAttribute('type', 'search');
    }

    public function addEmailText($name, $caption = NULL, $cols = NULL, $maxLength = NULL): \Nette\Forms\Controls\TextInput
    {
        return $this->addText($name, $caption, $cols = NULL, $maxLength = NULL)->setHtmlAttribute('role', 'textbox')->setHtmlAttribute('type', 'email');
    }

    public function addText(string $name, $caption = NULL, int $cols = NULL, int $maxLength = NULL): \Nette\Forms\Controls\TextInput
    {
		$control = new TextInput($caption, $maxLength);
		$control->setHtmlAttribute('size', $cols);
		return $this[$name] = $control->setHtmlAttribute('role', 'textbox');
    }

    public function addCheckbox(string $name, $caption = NULL): \Nette\Forms\Controls\Checkbox
    {
        return ($this[$name] = (new Checkbox($caption)));
    }

    public function addCalendar($name, $caption = NULL): Calendar
    {
        return ($this[$name] = (new Calendar($caption))->setHtmlAttribute('role', 'textbox'));
    }

    public function addDateTimeInput($name, $caption = NULL, $type = DateTimeInput::TYPE_DATETIME_NOSEC): DateTimeInput
    {
        return ($this[$name] = (new DateTimeInput($caption, $type))->setHtmlAttribute('role', 'textbox'));
    }

    public function addPhoneInput($name, $caption = NULL): PhoneInput
    {
        return ($this[$name] = (new PhoneInput($caption))->setHtmlAttribute('role', 'textbox'));
    }

    public function addButton(string $name, $caption = NULL): \Nette\Forms\Controls\Button
    {
        return parent::addButton($name, $caption)->setHtmlAttribute('role', 'button');
    }

    public function addSelect(string $name, $label = NULL, array $items = NULL, int $size = NULL): \Nette\Forms\Controls\SelectBox
    {
        return parent::addSelect($name, $label, $items, $size)->setHtmlAttribute('role', 'combobox');
    }

    public function addSubmit(string $name, $caption = NULL): \Nette\Forms\Controls\SubmitButton
    {
        return parent::addSubmit($name, $caption)->setHtmlAttribute('role', 'button');
    }

    public function addTextArea(string $name, $label = NULL, int $cols = NULL, int $rows = NULL): \Nette\Forms\Controls\TextArea
    {
        return parent::addTextArea($name, $label, $cols, $rows)->setHtmlAttribute('role', 'dek-textarea');
    }

    public function addUpload(string $name, $label = NULL, $multiple = FALSE): \Nette\Forms\Controls\UploadControl
    {
        return parent::addUpload($name, $label, $multiple)->setHtmlAttribute('role', 'dek-upload');
    }

    /**
     * 
     * @param string $caption
     * @param [] $items
     * @return RadioList
     */
    public function addRadios($caption = NULL, $items): RadioList
    {
        foreach ($items as $i) {
            $i->setForm($this);
        }
        $fitem = reset($items);
        return ($this[$fitem->getRadioName()] = (new RadioList($caption, $items)));
    }

}
