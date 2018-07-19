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

}
