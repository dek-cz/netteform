<?php

namespace DekApps\Form\Controls;

use Nette\Forms\IControl,
    Nette\Utils\IHtmlString,
    Nette\Forms\Controls\BaseControl,
    Nette\Utils\Html,
    DekApps\Form\Renderer;

class DateTimeInput extends BaseControl
{

    use TWrapp;
    /*
     * types of datetime
     */

    const TYPE_DATETIME = 'dekdatetime',
            TYPE_DATETIME_NOSEC = 'dekdatetimenosec',
            TYPE_DATE = 'dekdate';


    /*
     * css theme const
     */
    const DARKTHEME = 'dark-theme',
            TRIANGLETHEME = 'triangle-theme';
    const VALIDDATE = __CLASS__ . '::validateDateInputValid';

    /** @var string */
    protected $type;

    /** @var array */
    protected $range = array('min' => null, 'max' => null);

    /** @var mixed */
    protected $submitedValue = null;

    /** js,css load controling */
    private static $cssjsIsSet;

    /** date(time) php formats */
    private static $formats = array(
        self::TYPE_DATETIME => ['cz' => 'd.m.Y H:i:s', 'sk' => 'd.m.Y H:i:s',],
        self::TYPE_DATETIME_NOSEC => ['cz' => 'd.m.Y H:i', 'sk' => 'd.m.Y H:i',],
        self::TYPE_DATE => ['cz' => 'd.m.Y', 'sk' => 'd.m.Y',],
    );
    private $lang = 'cz';

    /**
     * css theme property
     * @var string 
     */
    private $theme = '';

    /**
     * 
     * @var bool 
     */
    private $showWeekNumber = false;

    /**
     * first day of the week (0: Sunday, 1: Monday, etc)
     * @var int 
     */
    private $firstDay = 1;
    private $showTime = true;
    private $showMinutes = true;
    private $showSeconds = false;
    private $use24hour = true;
    private $incrementHourBy = 1;
    private $incrementMinuteBy = 1;
    private $incrementSecondBy = 1;
    private $autoClose = true;
    private $timeLabel = null;

    /** @var ?DateTime */
    private $defaultDate = null;

    /** @var ?DateTime */
    private $pickerDefaultDate = null;

    /** @var ?integer */
    private $pickerDefaultYear = null;

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
     * @param string
     * @throws \InvalidArgumentException
     */
    public function __construct($label = null, $type = self::TYPE_DATETIME_NOSEC)
    {
        if (!isset(self::$formats[$type][$this->getLang()])) {
            throw new \InvalidArgumentException("invalid type '$type' given.");
        }
        parent::__construct($label);
        $this->control->type = $this->type = $type;
        $this->control->data('dateinput-type', $type);
        if ($type === self::TYPE_DATE) {
            $this->showTime = false;
        } elseif ($type === self::TYPE_DATETIME) {
            $this->showSeconds = true;
        } elseif ($type === self::TYPE_DATETIME_NOSEC) {
            //default
        }
    }

    public function setValue($value = null)
    {
        if ($value === null || $value instanceof \DateTime) {
            $this->value = $value;
            $this->submitedValue = null;
        } elseif (is_string($value)) {
            if ($value === '') {
                $this->value = null;
                $this->submitedValue = null;
            } else {
                $this->value = $this->parseValue($value);
                if ($this->value !== false) {
                    $this->submitedValue = null;
                } else {
                    $this->value = null;
                    $this->submitedValue = $value;
                }
            }
        } else {
            $this->submitedValue = $value;
            throw new \InvalidArgumentException("Invalid type for \$value.");
        }
        return $this;
    }

    public function getControl($wrap = true)
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this);
        }

        $global = $css = $js = $jsd = $jsutils = '';
        $c = parent::getControl();
        $php = $this->getPhpPartControl(parent::getControl());
        $template = new \Latte\Engine;
        if (!Renderer::getJsIsSet()) {
            $template = new \Latte\Engine;
            $global .= Html::el()->setHtml($template->renderToString(dirname(__FILE__) . '/templates/utils.js.latte'));
            Renderer::setJsIsSet(true);
        }
        if (!self::getCssjsIsSet()) {
            $jsd = Html::el()->setHtml($template->renderToString(dirname(__FILE__) . '/templates/datetimeinputdynamic.js.latte'));
            $jsutils = Html::el()->setHtml($template->renderToString(dirname(__FILE__) . '/templates/datetimeinput.js.latte'));
            ;
            $css = Html::el('link')->addAttributes(['type' => 'text/css', 'rel' => 'stylesheet'])->setHref('/assets/vendor/pikaday-time/css/pikaday.css');
            if ($this->getTheme()) {
                $css .= Html::el('link')->addAttributes(['type' => 'text/css', 'rel' => 'stylesheet'])->setHref('/assets/vendor/pikaday-time/css/' . ($this->getTheme() === self::DARKTHEME ? 'theme' : 'triangle') . '.css');
            }
            $js = Html::el('script')->addAttributes(['type' => 'text/javascript'])->setSrc('/assets/vendor/pikaday-time/pikaday.js');
            self::setCssjsIsSet(true);
        } else {
//            $jsd = Html::el()->setHtml($template->renderToString(dirname(__FILE__) . '/templates/datetimeinputdynamic.js.latte'));
        }
        return $global . $css . $jsutils . $js . $jsd . $php;
    }

    public function getPhpPartControl(IHtmlString $control)
    {
        $format = self::$formats[$this->type][$this->getLang()];
        if ($this->value !== null) {
            $control->value = $this->value->format($format);
        }
        if ($this->submitedValue !== null && is_string($this->submitedValue)) {
            $control->value = $this->submitedValue;
        }
        if ($control->value) {
            $control->addAttributes(['pickaday-defaultDate' => $control->value]);
        } else {
            $control->addAttributes(['pickaday-defaultDate' => $this->getDefaultDate()->format($format)]);
        }
        if ($this->range['min'] !== null) {
            $control->addAttributes(['pickaday-rangeMin' => $this->range['min']->format($format)]);
        }
        if ($this->range['max'] !== null) {
            $control->addAttributes(['pickaday-rangeMax' => $this->range['max']->format($format)]);
        }

        $control->addAttributes([
            'pickaday-showWeekNumber' => $this->getShowWeekNumber() ? 1 : 0,
            'pickaday-theme' => $this->getTheme(),
            'pickaday-firstDay' => $this->getFirstDay(),
            'pickaday-showTime' => $this->getShowTime() ? 1 : 0,
            'pickaday-showMinutes' => $this->getShowMinutes() ? 1 : 0,
            'pickaday-showSeconds' => $this->getShowSeconds() ? 1 : 0,
            'pickaday-use24hour' => $this->getUse24hour() ? 1 : 0,
            'pickaday-incrementHourBy' => $this->getIncrementHourBy(),
            'pickaday-incrementMinuteBy' => $this->getIncrementMinuteBy(),
            'pickaday-incrementSecondBy' => $this->getIncrementSecondBy(),
            'pickaday-autoClose' => $this->getAutoClose() ? 1 : 0,
            'pickaday-timeLabel' => $this->getTimeLabel(),
            'pickaday-format' => $format,
            'pickaday-pickerDefaultDate' => $this->getPickerDefaultDate(),
            'pickaday-pickerDefaultYear' => $this->getPickerDefaultYear(),
            'pickaday-lang' => $this->getLang()
        ]);

        $err = $this->getErrors();
        if ($err) {
            $control->class('error', true);
        }

        return $control;
    }

    public function addRule($operation, $message = null, $arg = null)
    {
        if ($operation === \Nette\Forms\Form::RANGE) {
            $this->range['min'] = $this->normalizeDate($arg[0]);
            $this->range['max'] = $this->normalizeDate($arg[1]);
            $operation = __CLASS__ . '::validateDateInputRange';
            $arg[0] = $this->formatDate($arg[0]);
            $arg[1] = $this->formatDate($arg[1]);
        }
        if ($operation === \Nette\Forms\Form::MIN) {
            $this->range['min'] = $this->normalizeDate($arg[0]);
            $arg[0] = $this->formatDate($arg[0]);
            $operation = __CLASS__ . '::validateDateInputMin';
        }
        return parent::addRule($operation, $message, $arg);
    }

    public static function validateFilled(IControl $control)
    {
        if (!$control instanceof self) {
            throw new \InvalidArgumentException("Cant't validate control '" . \get_class($control) . "'.");
        }
        return ($control->value !== null || $control->submitedValue !== null);
    }

    /**
     * Valid validator: is control valid?
     * @param  IControl
     * @return bool
     */
    public static function validateDateInputValid(IControl $control)
    {
        return self::validateValid($control);
    }

    /**
     * Valid validator: is control valid?
     * @param  IControl
     * @return bool
     */
    public static function validateDateInputMin(IControl $control)
    {
        return self::validateValid($control);
    }

    public static function validateValid(IControl $control)
    {
        if (!$control instanceof self) {
            throw new \InvalidArgumentException("Cant't validate control '" . \get_class($control) . "'.");
        }
        return $control->submitedValue === null;
    }

    /**
     * @param self $control
     * @param array $args
     * @return bool
     */
    public static function validateDateInputRange(self $control)
    {

        if ($control->range['min'] !== null) {
            if ($control->range['min'] > $control->value) {
                return false;
            }
        }
        if ($control->range['max'] !== null) {
            if ($control->range['max'] < $control->value) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $value
     * @return \DateTime
     */
    private function parseValue($value)
    {
        $date = \DateTime::createFromFormat('!' . self::$formats[$this->type][$this->getLang()], $value);
        return $date;
    }

    /**
     * @param \DateTime $value
     * @return string
     */
    private function formatDate(\DateTime $value = null)
    {
        if ($value) {
            $value = $value->format(self::$formats[$this->type][$this->getLang()]);
        }
        return $value;
    }

    /**
     * @param \DateTime
     * @return \DateTime
     */
    private function normalizeDate(\DateTime $value = null)
    {
        if ($value) {
            $value = $this->formatDate($value);
            $value = $this->parseValue($value);
        }
        return $value;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($theme)
    {
        $this->theme = in_array($theme, [self::DARKTHEME, self::TRIANGLETHEME, '']) ? $theme : $this->theme;
        return $this;
    }

    public function getShowWeekNumber()
    {
        return $this->showWeekNumber;
    }

    public function setShowWeekNumber($showWeekNumber)
    {
        $this->showWeekNumber = $showWeekNumber;
        return $this;
    }

    public function getFirstDay()
    {
        return $this->firstDay;
    }

    public function setFirstDay($firstDay)
    {
        $this->firstDay = is_numeric($firstDay) ? $firstDay : $this->firstDay;
        return $this;
    }

    public function getShowTime()
    {
        return $this->showTime;
    }

    public function getShowMinutes()
    {
        return $this->showMinutes;
    }

    public function getShowSeconds()
    {
        return $this->showSeconds;
    }

    public function getUse24hour()
    {
        return $this->use24hour;
    }

    public function getIncrementHourBy()
    {
        return $this->incrementHourBy;
    }

    public function getIncrementMinuteBy()
    {
        return $this->incrementMinuteBy;
    }

    public function getIncrementSecondBy()
    {
        return $this->incrementSecondBy;
    }

    public function getAutoClose()
    {
        return $this->autoClose;
    }

    public function getTimeLabel()
    {
        return $this->timeLabel;
    }

    public function setUse24hour($use24hour)
    {
        $this->use24hour = $use24hour;
        return $this;
    }

    public function setIncrementHourBy($incrementHourBy)
    {
        $this->incrementHourBy = $incrementHourBy;
        return $this;
    }

    public function setIncrementMinuteBy($incrementMinuteBy)
    {
        $this->incrementMinuteBy = $incrementMinuteBy;
        return $this;
    }

    public function setIncrementSecondBy($incrementSecondBy)
    {
        $this->incrementSecondBy = $incrementSecondBy;
        return $this;
    }

    public function setAutoClose($autoClose)
    {
        $this->autoClose = $autoClose;
        return $this;
    }

    public function setTimeLabel($timeLabel)
    {
        $this->timeLabel = $timeLabel;
        return $this;
    }

    public function getDefaultDate()
    {
        return $this->defaultDate;
    }

    public function setDefaultDate(\DateTime $defaultDate)
    {
        $this->defaultDate = $defaultDate;
        return $this;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getPickerDefaultDate()
    {
        return $this->pickerDefaultDate;
    }

    public function getPickerDefaultYear()
    {
        return $this->pickerDefaultYear;
    }

    public function setPickerDefaultDate(\DateTime $pickerDefaultDate)
    {
        $this->pickerDefaultDate = $pickerDefaultDate;
        return $this;
    }

    public function setPickerDefaultYear($pickerDefaultYear)
    {
        $this->pickerDefaultYear = $pickerDefaultYear;
        return $this;
    }

}
