<?php

namespace DekApps\Form\Controls;

use Nette\Bridges\ApplicationLatte\Template;
use Nette\Forms\Controls\UploadControl;
use Nette\Utils\Html;

class MultiUpload extends UploadControl
{

    use TWrapp;

    private $maxFileSize  = 4 * 1024 * 1024;
    private $maxFileCount = 10;

    public function __construct($label, $maxFileSize, $maxFileCount)
    {
        parent::__construct($label, true);
        $this->maxFileSize  = $maxFileSize;
        $this->maxFileCount = $maxFileCount;
    }

    public function getControl($wrap = true): Html
    {
        if ($this->getDekWrapper() && $wrap && $this->getForm() && $this->getForm()->getRenderer()) {
            return Html::el()->setHtml($this->getForm()->getRenderer()->renderLabel($this) . $this->getForm()->getRenderer()->renderControl($this));
        }

        $inputHmtl = parent::getControl();
        $inputHmtl->setHtmlAttribute('class', 'form__upload');
        $inputHmtl->setHtmlAttribute('role', 'upload');
        $template  = new Template(new \Latte\Engine());

        $template->inputId      = $inputHmtl->id;
        $template->maxFileSize  = $this->maxFileSize;
        $template->maxFileCount = $this->maxFileCount;

        $html = Html::el();
        $html->addHtml($inputHmtl);
        $html->addHtml($template->renderToString(dirname(__FILE__) . '/templates/multiupload.js.latte'));
        return $html;
    }

}
