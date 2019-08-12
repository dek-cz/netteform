<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\TextInput,
    Nette\Utils\Html;

/**
 * Javascript doc https://anticz.github.io/day-of-ranger/
 */
class Calendar extends TextInput
{
    use TWrapp;
    
    public function getControl($wrap = true)
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this);
        }

        $inputHmtl = parent::getControl();
        $inputHmtl->addAttributes(['readonly' => 'readonly']);
        $inputHmtl->addAttributes(['onfocus' => 'this.setAttribute("readonly", "readonly")']);
        $inputHmtl->addAttributes(['data-input-type' => 'dek-calendar']);
        $template = new \Latte\Engine;
        
        $err = $this->getErrors();
        $script = '';
        if ($err) {
            $errorMessage = is_array($err) && isset($err[0]) ? $err[0]: '';
            $inputHmtl->class('error', true);
            $script = "<script>(function(){var el = document.getElementById('$inputHmtl->id');el.setCustomValidity('$errorMessage');el.removeAttribute('readonly');el.reportValidity();})();</script>";
        }
        
        return Html::el()->setHtml($template->renderToString(dirname(__FILE__) . '/templates/calendar.js.latte')) . $inputHmtl.$script;
    }
}
