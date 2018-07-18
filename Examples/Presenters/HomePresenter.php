<?php

namespace Examples\Presenters;

use Nette\Application\UI\Presenter;
use Examples\Forms\FormFactory;
use DekApps\Form\Controls\DateTimeInput;
use DekApps\Form\Controls\PhoneInput;

class HomepagePresenter extends Presenter
{

	/** @var FormFactory @inject */
	public $formFactory;

	protected function createComponentTestForm()
	{
		$form = $this->formFactory->create();
		$form->addText('firstname', 'form.employment.firtstname')->setRequired('form.defaults.messages.required');
		$form->addText('surname', 'form.employment.surname')->setRequired('form.defaults.messages.required');
		$form->addText('title', 'form.employment.title');
		$form->addDateTimeInput('birthdate', 'form.employment.birthdate', DateTimeInput::TYPE_DATE)->addRule(\Nette\Forms\Form::RANGE, 'form.defaults.messages.dateinterval', array(new \DateTime('1970-01-01'), new \DateTime('2006-12-31')))->setDefaultDate(new \DateTime('2009-04-30'))->setTheme(DateTimeInput::DARKTHEME)->setRequired('form.defaults.messages.required');
		$form->addDateTimeInput('interviewdate', 'form.employment.interviewdate')->addRule(\Nette\Forms\Form::RANGE, 'form.defaults.messages.dateinterval', array((new \DateTime())->modify('-1 hour'), new \DateTime('9999-12-31 12:00:00')))->setDefaultDate(new \DateTime('2018-11-30 13:33:27'));
		$form->addPhoneInput('phoneNum', 'form.employment.phonenum')->setRequired('form.defaults.messages.required')->addCondition(\Nette\Application\UI\Form::FILLED)->addRule(PhoneInput::VALIDPHONENUM, 'form.defaults.messages.phonenum');
		
		$form->addCheckbox('function', 'form.nastaveni_cookies.function.title')->setText('form.nastaveni_cookies.function.text');
		$form->addSubmit('submit', 'form.nastaveni_cookies.submit_button')->setAttribute('role', 'button')->setAttribute('class', 'dek-button-green');

		$form->onSuccess[] = function ($form)
		{
			$form->getPresenter()->redirect('Homepage:');
		};
		return $form;
	}

	public function renderDefault()
	{
		
	}

}
