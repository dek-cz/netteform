<?php

namespace DekApps\Form\Controls;

use Nette\Forms\Controls\Checkbox as BaseRadio;
use Nette\Utils\Html;

class Radio extends BaseRadio
{

    use TWrapp;

    const TITLE = 'title';
    const TEXT = 'text';

    /** @var string */
    protected $radioName = '';

    /** @var string */
    protected $text;

    /** @var bool */
    protected $readonly = '';

    /**
     * 'title'=>[]
     * 'text'=>[]
     * 
     * @var []
     */
    protected $textParams = [];
    private $form;
    private $checked = false;
    private $icon;

    public function __construct($name, $label = NULL, $form = null)
    {
        parent::__construct($label);
        $this->control->type = 'radio';
        $this->setAttribute('role', 'radio');
        if ($name) {
            $this->radioName = $name;
        }
        $this->control->id = 'rd-' . md5(rand(0, 999) . $this->radioName . microtime(true));
        $this->form = $form;
    }

    public function setText($text): self
    {
        if (!is_string($text)) {
            throw new \Exception(sprintf("Value must be STRING, %s given in field '%s'.", gettype($text), $this->name));
        }
        $this->text = $text;
        return $this;
    }

    public function setReadOnly(): self
    {
        $this->readonly = 'readonly';
        $this->setAttribute('readonly', 'readonly');
        $this->setAttribute('onclick', 'return false;');
        return $this;
    }

    public function getReadonly(): string
    {
        return $this->readonly;
    }

    /**
     * Returns control's value.
     * @return mixed
     */
    public function getText(): mixed
    {
        return $this->text;
    }

    public function getControl($wrap = true): Html
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return $this->getForm()->getRenderer()->renderControl($this);
        }
        $label = $this->getLabelPart();
        $label->setText('');
        $label->addClass('dek-radio');
        if ($this->isDisabled()) {
            $label->addClass('disabled');
        }
        $label->insert(0, $this->getControlPart());
        $label->insert(1, \Nette\Utils\Html::el('span')->setClass('dek-radio__check'));
        if ($this->icon) {
            $label->insert(2, \Nette\Utils\Html::el('img')->setAttribute('src', $this->icon));
        }
        $label->insert(3, \Nette\Utils\Html::el('span')->setClass('dek-radio__label')->setHtml($this->translate($this->caption), isset($this->textParams[self::TITLE]) ? $this->textParams[self::TITLE] : []));
        if ($this->text) {
            $label->insert(4, \Nette\Utils\Html::el('span')->setClass('dek-radio__text')->setHtml($this->translate($this->text), isset($this->textParams[self::TEXT]) ? $this->textParams[self::TEXT] : []));
        }

        return $this->getSeparatorPrototype()->setHtml($label);
    }

    public function setTitleParams($params): self
    {

        $this->textParams[self::TITLE] = $params;
        return $this;
    }

    public function setTextParams($params): self
    {

        $this->textParams[self::TEXT] = $params;
        return $this;
    }

    public function getRadioName(): string
    {
        return $this->radioName;
    }

    public function setRadioName(string $radioName): self
    {
        $this->radioName = $radioName;
        return $this;
    }

    public function getHtmlName(): string
    {
        if ($this->getRadioName()) {
            return $this->getRadioName();
        }
        return parent::getHtmlName();
    }

    public function getForm(bool $need = TRUE): \Nette\Forms\Form
    {
        return $this->form;
    }

    public function setForm(\Nette\Forms\Form $form): self
    {
        $this->form = $form;
        return $this;
    }

    public function setValue(mixed $value): self
    {
        if (!is_scalar($value) && $value !== NULL) {
            throw new \Nette\InvalidArgumentException(sprintf("Value must be scalar or NULL, %s given in field '%s'.", gettype($value), $this->name));
        }
        $this->value = $value;
        return $this;
    }

    /**
     * @return Html
     */
    public function getControlPart(): Html
    {

        $this->setOption('rendered', TRUE);
        $el = clone $this->control;
        $attr = array(
            'name' => $this->getHtmlName(),
            'id' => $this->getHtmlId(),
            'required' => $this->isRequired(),
//                    'checked' => $this->getChecked()?'checked':'',
            'disabled' => $this->isDisabled(),
            'value' => $this->value,
            'data-nette-rules' => \Nette\Forms\Helpers::exportRules($this->getRules()) ?: NULL,
        );
        if ($this->getChecked()) {
            $attr['checked'] = 'checked';
        }
        return $el->addAttributes($attr);
    }

    public function getChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     * @return $this
     */
    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;
        return $this;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Disables or enables control.
     * @param  bool  $value
     * @return static
     */
    public function setDisabled($value = true): self
    {
        if ($this->disabled = (bool) $value) {
            //not if radio
            //$this->setValue(null);
        } elseif (($form = $this->getForm(false)) && $form->isAnchored() && $form->isSubmitted()) {
            $this->loadHttpData();
        }
        return $this;
    }

}
