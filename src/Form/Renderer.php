<?php

namespace DekApps\Form;

use Nette;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Forms\Controls;

class Renderer extends DefaultFormRenderer
{

	public function __construct()
	{

		$this->wrappers['controls']['container'] = null;
		$this->wrappers['pair']['container'] = null;
		$this->wrappers['label']['container'] = null;
		$this->wrappers['control']['container'] = null;
		$this->wrappers['comboinputpair']['container'] = 'div';
		$this->wrappers['comboinputpair']['.primaryClass'] = 'dek-input-phone';
	}

	public function renderBegin()
	{
		$this->form->getElementPrototype()->setRole('form');
		return parent::renderBegin();
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
		$el = $control->getControl();
		if ($el instanceof Html && $el->getName() === 'input') {
			$el->class($this->getValue("control .$el->type"), TRUE);
		}
		return $body->setHtml($el . $description . $this->renderErrors($control));
	}

}
