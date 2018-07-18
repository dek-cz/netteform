<?php

namespace DekApps\Form;

use Nette\Application\UI\Form as BaseForm;
use \DekApps\Form\Controls\Checkbox;
use \DekApps\Form\Controls\DateTimeInput;
use \DekApps\Form\Controls\PhoneInput;
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
    }

    public function addCheckbox($name, $caption = NULL)
    {
        return ($this[$name] = (new Checkbox($caption))->setAttribute('role', 'checkbox'));
    }
    
    public function addDateTimeInput($name, $caption = NULL, $type = DateTimeInput::TYPE_DATETIME_NOSEC)
    {
        return ($this[$name] = (new DateTimeInput($caption, $type))->setAttribute('role', 'datetime'));
    }
    public function addPhoneInput($name, $caption = NULL)
    {
        return ($this[$name] = (new PhoneInput($caption))->setAttribute('role', 'phone'));
    }

}
