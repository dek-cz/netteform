<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\BaseControl as BaseControl,
    Nette\Utils\Strings,
    Nette\Utils\Html,
    Nette\Forms\Form;

class PhoneInput extends BaseControl
{
    use TWrapp;

    const NAME_PREFIX = 'phoneprefix',
            NAME_NUMBER = 'phonenumber',
            VALIDPHONENUM = __CLASS__ . '::validatePhoneNumber';

    /** @var array */
    protected static $phonePrefixes = array(
        '+1',
        '+20',
        '+27',
        '+28',
        '+210',
        '+211',
        '+212',
        '+213',
        '+214',
        '+215',
        '+216',
        '+217',
        '+218',
        '+219',
        '+220',
        '+221',
        '+222',
        '+223',
        '+224',
        '+225',
        '+226',
        '+227',
        '+228',
        '+229',
        '+230',
        '+231',
        '+232',
        '+233',
        '+234',
        '+235',
        '+236',
        '+237',
        '+238',
        '+239',
        '+240',
        '+241',
        '+242',
        '+243',
        '+244',
        '+245',
        '+246',
        '+247',
        '+248',
        '+249',
        '+250',
        '+251',
        '+252',
        '+253',
        '+254',
        '+255',
        '+256',
        '+257',
        '+258',
        '+259',
        '+260',
        '+261',
        '+262',
        '+263',
        '+264',
        '+265',
        '+266',
        '+267',
        '+268',
        '+269',
        '+290',
        '+291',
        '+292',
        '+293',
        '+294',
        '+295',
        '+296',
        '+297',
        '+298',
        '+299',
        '+30',
        '+31',
        '+32',
        '+33',
        '+34',
        '+36',
        '+39',
        '+350',
        '+351',
        '+352',
        '+353',
        '+354',
        '+355',
        '+356',
        '+357',
        '+358',
        '+359',
        '+370',
        '+371',
        '+372',
        '+373',
        '+374',
        '+375',
        '+376',
        '+377',
        '+378',
        '+379',
        '+380',
        '+381',
        '+382',
        '+383',
        '+384',
        '+385',
        '+386',
        '+387',
        '+388',
        '+389',
        '+40',
        '+41',
        '+43',
        '+44',
        '+45',
        '+46',
        '+47',
        '+48',
        '+49',
        '+420',
        '+421',
        '+422',
        '+423',
        '+424',
        '+425',
        '+426',
        '+427',
        '+428',
        '+429',
        '+51',
        '+52',
        '+53',
        '+54',
        '+55',
        '+56',
        '+57',
        '+58',
        '+500',
        '+501',
        '+502',
        '+503',
        '+504',
        '+505',
        '+506',
        '+507',
        '+508',
        '+509',
        '+590',
        '+591',
        '+592',
        '+593',
        '+594',
        '+595',
        '+596',
        '+597',
        '+598',
        '+599',
        '+60',
        '+61',
        '+62',
        '+63',
        '+64',
        '+65',
        '+66',
        '+670',
        '+671',
        '+672',
        '+673',
        '+674',
        '+675',
        '+676',
        '+677',
        '+678',
        '+679',
        '+680',
        '+681',
        '+682',
        '+683',
        '+684',
        '+685',
        '+686',
        '+687',
        '+688',
        '+689',
        '+690',
        '+691',
        '+692',
        '+693',
        '+694',
        '+695',
        '+696',
        '+697',
        '+698',
        '+699',
        '+7',
        '+800',
        '+801',
        '+802',
        '+803',
        '+804',
        '+805',
        '+806',
        '+807',
        '+808',
        '+809',
        '+81',
        '+82',
        '+83',
        '+84',
        '+86',
        '+89',
        '+850',
        '+851',
        '+852',
        '+853',
        '+854',
        '+855',
        '+856',
        '+857',
        '+858',
        '+859',
        '+870',
        '+875',
        '+876',
        '+877',
        '+878',
        '+879',
        '+880',
        '+881',
        '+882',
        '+883',
        '+884',
        '+885',
        '+886',
        '+887',
        '+888',
        '+889',
        '+90',
        '+91',
        '+92',
        '+93',
        '+94',
        '+95',
        '+98',
        '+960',
        '+961',
        '+962',
        '+963',
        '+964',
        '+965',
        '+966',
        '+967',
        '+968',
        '+969',
        '+970',
        '+971',
        '+972',
        '+973',
        '+974',
        '+975',
        '+976',
        '+977',
        '+978',
        '+979',
        '+990',
        '+991',
        '+992',
        '+993',
        '+994',
        '+995',
        '+996',
        '+997',
        '+998',
        '+999',
    );

    /** js,css load controling */
    private static $cssjsIsSet = false;

    /** @var string */
    private $prefix;

    /** @var string */
    private $number;

    /** @var string */
    private $defaultPrefix = '+420';

    public static function getCssjsIsSet()
    {
        return self::$cssjsIsSet;
    }

    public static function setCssjsIsSet($cssjsIsSet)
    {
        self::$cssjsIsSet = $cssjsIsSet;
    }

    /**
     * @param string
     * @return \Nella\Forms\Phone\PhoneNumberInput
     * @throws \Nette\InvalidArgumentException
     */
    public function setValue($value)
    {
        if ($value === NULL) {
            $this->prefix = NULL;
            $this->number = NULL;
            return $this;
        }
        $value = self::normalizePhoneNumber($value);
        if (!self::validatePhoneNumberString($value)) {
            throw new \Nette\InvalidArgumentException('Value must starts with + and numbers, "' . $value . '" given.');
        }
        $data = Strings::match($value, self::getPattern());
        if (!isset($data[static::NAME_PREFIX]) || !isset($data[static::NAME_NUMBER])) {
            throw new \Nette\InvalidArgumentException('Value must starts with + and numbers, "' . $value . '" given.');
        }
        $this->prefix = $data[static::NAME_PREFIX];
        $this->number = $data[static::NAME_NUMBER];
        return $this;
    }

    /**
     * @return string|NULL
     */
    public function getValue()
    {
        if (empty($this->prefix) || empty($this->prefix)) {
            return NULL;
        }
        $value = self::normalizePhoneNumber($this->prefix . $this->number);
        if (!self::validatePhoneNumberString($value)) {
            return NULL;
        }
        return $value;
    }

    /**
     * @param string
     * @return \Nella\Forms\Phone\PhoneNumberInput
     */
    public function setDefaultPrefix($prefix)
    {
        if (!in_array($prefix, static::$phonePrefixes, TRUE) && !is_null($prefix)) {
            throw new \Nette\InvalidArgumentException('This prefix is not supported');
        }
        $this->defaultPrefix = $prefix;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilled()
    {
        return !empty($this->number);
    }

    public function loadHttpData()
    {
        $this->prefix = $this->getHttpData(Form::DATA_LINE, '[' . static::NAME_PREFIX . ']');
        $this->number = $this->getHttpData(Form::DATA_LINE, '[' . static::NAME_NUMBER . ']');
    }

    public function getControl($wrap = true)
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this);
        }
        $s = '';
        if (!self::getCssjsIsSet()) {
            $template = new \Latte\Engine;
            $s .= Html::el()->setHtml($template->renderToString(dirname(__FILE__) . '/templates/phoneinput.js.latte'));
            self::setCssjsIsSet(true);
        }
        return $s . $this->getControlPart(static::NAME_PREFIX) . $this->getControlPart(static::NAME_NUMBER);
    }

    /**
     * @param string
     * @return \Nette\Utils\Html
     * @throws \Nette\InvalidArgumentException
     */
    public function getControlPart($key)
    {
        $name = $this->getHtmlName();
        if ($key === static::NAME_PREFIX) {
            $control = \Nette\Forms\Helpers::createSelectBox(
                            array_combine(static::$phonePrefixes, static::$phonePrefixes), [
                        'selected?' => $this->prefix === NULL ? $this->defaultPrefix : $this->prefix
                            ]
            );
            $control->addAttributes(['style'=>'margin-top: 5px;']);
            $control->setRole('combobox');
            $control->name($name . '[' . static::NAME_PREFIX . ']')->id($this->getHtmlId());
            if ($this->disabled) {
                $control->disabled($this->disabled);
            }
            return $control;
        } elseif ($key === static::NAME_NUMBER) {
            $control = \Nette\Utils\Html::el('input')->name($name . '[' . static::NAME_NUMBER . ']');
            $control->value($this->number);
            $control->type('tel');
            $control->setRole('textbox');
            if ($this->disabled) {
                $control->disabled($this->disabled);
            }
            $control->addAttributes(['data-nette-rules' => \Nette\Forms\Helpers::exportRules($this->rules) ?: NULL]);

            $err = $this->getErrors();
            if ($err) {
                $control->class('error', true);
            }
            return $control;
        }
        throw new \Nette\InvalidArgumentException('Part ' . $key . ' does not exist');
    }

    public function getLabelPart()
    {
        return null;
    }

    /**
     * @param \Nette\Forms\IControl
     * @return bool
     */
    public static function validatePhoneNumber(\Nette\Forms\IControl $control)
    {
        $value = $control->getHttpData(Form::DATA_LINE, '[' . static::NAME_PREFIX . ']');
        $value .= $control->getHttpData(Form::DATA_LINE, '[' . static::NAME_NUMBER . ']');
        return self::validatePhoneNumberString($value);
    }

    /**
     * @param string
     * @return bool
     */
    private static function validatePhoneNumberString($value)
    {
        $value = self::normalizePhoneNumber($value);
        return (bool) Strings::match($value, self::getPattern());
    }

    /**
     * @return string
     */
    private static function getPattern()
    {
        return '~^(?P<' . static::NAME_PREFIX . '>\\' . implode('|\\', static::$phonePrefixes) . ')(?P<' . static::NAME_NUMBER . '>\d{6,15})$~';
    }

    /**
     * @param string
     * @return string
     */
    private static function normalizePhoneNumber($value)
    {
        $value = Strings::replace($value, array(
                    '~(\s+|\.|-)~' => '', // remove separators
                    '~^00(\d{10,16})$~' => '+$1$2',
                    '~^(\d{9,18})$~' => '+$1$2', // missing +
        ));
        return $value;
    }

}
