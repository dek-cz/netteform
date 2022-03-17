<?php

namespace DekApps\Form\Controls;

use Nette\Bridges\ApplicationLatte\Template;
use Nette\Forms\Controls\UploadControl;
use Nette\Utils\Html;

class MultiUpload extends UploadControl
{

    use TWrapp;

    private $maxFileSize       = 4 * 1024 * 1024;
    private $maxFileCount      = 10;
    private $chooseFilesText   = 'Vybrat soubory';
    private $maxFileSizeError  = 'Maximální velikost souboru byla překročena.';
    private $maxFileCountError = 'Maximální počet souborů byl překročen.';

    public function __construct($label)
    {
        parent::__construct($label, true);
    }

    public function setMaxFileSize($maxFileSize)
    {
        $this->maxFileSize = $maxFileSize;
        return $this;
    }

    public function setMaxFileCount($maxFileCount)
    {
        $this->maxFileCount = $maxFileCount;
        return $this;
    }

    public function setChooseFilesText($chooseFilesText)
    {
        $this->chooseFilesText = $chooseFilesText;
        return $this;
    }

    public function setMaxFileSizeError($maxFileSizeError)
    {
        $this->maxFileSizeError = $maxFileSizeError;
        return $this;
    }

    public function setMaxFileCountError($maxFileCountError)
    {
        $this->maxFileCountError = $maxFileCountError;
        return $this;
    }

    public function getControl($wrap = true): Html
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return Html::el()->setHtml($this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this));
        }

        $inputHmtl = parent::getControl();
        $inputHmtl->addAttributes(['class' => 'form__upload']);
        $inputHmtl->addAttributes(['role' => 'upload']);
        $template  = new Template(new \Latte\Engine());

        $template->inputId      = $inputHmtl->id;
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
