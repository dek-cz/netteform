<?php

namespace DekApps\Form\Controls;

use DekApps\Form\Templates\MultiUploadTemplate as Template;
use Nette\Forms\Controls\UploadControl;
use Nette\Utils\Html;

class MultiUpload extends UploadControl
{

    use TWrapp;

    private int $maxFileSize       = 4 * 1024 * 1024;
    private int $maxFileCount      = 10;
    private string $chooseFilesText   = 'Vybrat soubory';
    private string $maxFileSizeError  = 'Maximální velikost souboru byla překročena.';
    private string $maxFileCountError = 'Maximální počet souborů byl překročen.';

    public function __construct(mixed $label)
    {
        parent::__construct($label, true);
    }

    public function setMaxFileSize(int $maxFileSize): static
    {
        $this->maxFileSize = $maxFileSize;
        return $this;
    }

    public function setMaxFileCount(int $maxFileCount): static
    {
        $this->maxFileCount = $maxFileCount;
        return $this;
    }

    public function setChooseFilesText(string $chooseFilesText): static
    {
        $this->chooseFilesText = $chooseFilesText;
        return $this;
    }

    public function setMaxFileSizeError(string $maxFileSizeError): static
    {
        $this->maxFileSizeError = $maxFileSizeError;
        return $this;
    }

    public function setMaxFileCountError(string $maxFileCountError): static
    {
        $this->maxFileCountError = $maxFileCountError;
        return $this;
    }

    public function getControl(bool $wrap = true): Html
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return Html::el()->setHtml($this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this));
        }

        $inputHmtl = parent::getControl();
        $inputHmtl->addAttributes(['class' => 'form__upload']);
        $inputHmtl->addAttributes(['role' => 'upload']);
        $template  = new Template(new \Latte\Engine());

        $template->inputId      = (string) $inputHmtl->id;
        $template->maxFileSize  = $this->maxFileSize;
        $template->maxFileCount = $this->maxFileCount;

        $template->chooseFilesText   = $this->chooseFilesText;
        $template->maxFileSizeError  = $this->maxFileSizeError;
        $template->maxFileCountError = $this->maxFileCountError;

        $html = Html::el();
        $html->addHtml($inputHmtl);
        $html->addHtml($template->renderToString(dirname(__FILE__) . '/templates/multiupload.js.latte'));
        return $html;
    }

}
