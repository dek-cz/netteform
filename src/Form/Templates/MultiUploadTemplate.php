<?php
namespace DekApps\Form\Templates;

use Nette\Bridges\ApplicationLatte\Template;

class MultiUploadTemplate extends Template
{
    public ?string $inputId = null;
    public ?int $maxFileSize = null;
    public ?int $maxFileCount = null;
    public ?string $chooseFilesText = null;
    public ?string $maxFileSizeError = null;
    public ?string $maxFileCountError = null;

}
