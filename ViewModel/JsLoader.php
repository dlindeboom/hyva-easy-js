<?php

declare(strict_types=1);

namespace Hyva\EasyJs\ViewModel;

use Hyva\EasyJs\Lib\JsRegistry;
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

    public function require(string $jsFilePath): JsRegistry
    {
        $path = $this->resolver->getTemplateFileName($jsFilePath);
        $content = $this->file->fileGetContents($path);
        $jsReqistry = new JsRegistry();
        $code = $jsReqistry->getCode();

        echo sprintf(<<<SCRIPT

            <script>

                const easyJs = (name, callback) => {
                    window['%s_' + name] = callback;
                };

                %s

            </script>

        SCRIPT, $code, $content);

        return $jsReqistry;
    }
}
