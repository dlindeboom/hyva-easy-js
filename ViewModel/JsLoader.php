<?php

declare(strict_types=1);

namespace Hyva\EasyJs\ViewModel;

use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template\File\Resolver;

class JsLoader implements ArgumentInterface
{
    private File $file;
    private Resolver $resolver;

    public function __construct(File $file, Resolver $resolver)
    {
        $this->file = $file;
        $this->resolver = $resolver;
    }

    public function require(string $jsFilePath): string
    {
        $path = $this->resolver->getTemplateFileName($jsFilePath);
        $content = $this->file->fileGetContents($path);

        return sprintf("<script>%s</script>", $content);
    }
}
