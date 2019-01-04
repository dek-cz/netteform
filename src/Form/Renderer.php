<?php

namespace DekApps\Form;

use Nette;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Forms\Controls;
use Nette\Utils\Html;

class Renderer extends DefaultFormRenderer
{

    /** js load controling */
    private static $jsIsSet = false;

    public static function getJsIsSet()
    {
        return self::$jsIsSet;
    }

    public static function setJsIsSet($jsIsSet)
    {
        self::$jsIsSet = $jsIsSet;
    }

    public function __construct()
    {

        $this->wrappers['error']['container'] = null;
        $this->wrappers['error']['item'] = 'div role=alert';
        $this->wrappers['controls']['container'] = null;
        $this->wrappers['pair']['container'] = null;
        $this->wrappers['label']['container'] = null;
        $this->wrappers['control']['container'] = null;
        $this->wrappers['control']['errorcontainer'] = null;
        $this->wrappers['control']['erroritem'] = 'div role=alert';
        $this->wrappers['comboinputpair']['container'] = 'div';
        $this->wrappers['comboinputpair']['.primaryClass'] = 'dek-input-phone';
        $this->wrappers['choicelist']['container'] = 'div';
        $this->wrappers['choicelist']['.primaryClass'] = 'dek-choice-list';
    }

    public function renderBegin()
    {
        $s = '';
        if (!self::getJsIsSet()) {
            $template = new \Latte\Engine;
            $s .= Html::el()->setHtml($template->renderToString(dirname(__FILE__) . '/Controls/templates/utils.js.latte'));
            self::setJsIsSet(true);
        }
        return $s . parent::renderBegin();
    }

    public function attachForm(Nette\Forms\Form $form)
    {
        $this->form = $form;
    }

    /**
     * Renders 'control' part of visual row of controls.
     * @return string
     */
    public function renderControl(Nette\Forms\IControl $control)
    {
        if ($control instanceof \DekApps\Form\Controls\PhoneInput) {
            $body = $this->getWrapper('comboinputpair container');
            $body->class($this->getValue('comboinputpair .primaryClass'), TRUE);
        } elseif ($control instanceof \DekApps\Form\Controls\RadioList) {
            $body = $this->getWrapper('choicelist container');
            $body->class($this->getValue('choicelist .primaryClass'), TRUE);
        } else {
            $body = $this->getWrapper('control container');
        }
        if ($this->counter % 2) {
            $body->class($this->getValue('control .odd'), TRUE);
        }

        $description = $control->getOption('description');
        if ($description instanceof Html) {
            $description = ' ' . $description;
        } elseif (is_string($description)) {
            $description = ' ' . $this->getWrapper('control description')->setText($control->translate($description));
        } else {
            $description = '';
        }

        if ($control->isRequired()) {
            $description = $this->getValue('control requiredsuffix') . $description;
        }

        $control->setOption('rendered', TRUE);
        $err = $this->renderErrors($control);

        $el = $control->getControl(false);
        if ($el instanceof Html && $el->getName() === 'input') {
            $el->class($this->getValue("control .$el->type"), TRUE);
        }

        if ($err && is_object($el)) {
            $el->class('error');
        }
        return $body->setHtml($el . $description . $err);
    }

}
