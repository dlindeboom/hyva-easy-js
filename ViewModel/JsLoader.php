<?php

declare(strict_types=1);

namespace Hyva\EasyJs\ViewModel;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template\File\Resolver;

class JsLoader implements ArgumentInterface
{
    private File $file;
    private Resolver $resolver;
    private CacheInterface $cache;

    public function __construct(File $file, Resolver $resolver, CacheInterface $cache)
    {
        $this->file = $file;
        $this->resolver = $resolver;
        $this->cache = $cache;
    }

    public function require(string $jsFilePath): string
    {
        $path = $this->resolver->getTemplateFileName($jsFilePath);
        $content = $this->file->fileGetContents($path);

        return sprintf("<script>%s</script>", $content);
    }

    public function requireOnce(string $jsFilePath): string
    {
        $cacheCode = $this->makeUniqueString($jsFilePath);
        $content = $this->cache->load(sprintf('js_load_%s', $this->makeUniqueString($jsFilePath)));

        if (empty($content)) {
            $path = $this->resolver->getTemplateFileName($jsFilePath);
            $content = $this->file->fileGetContents($path);

            $this->cache->save(sprintf('js_load_%s', $content));
        }

        $path = $this->resolver->getTemplateFileName($jsFilePath);
        $content = $this->file->fileGetContents($path);

        return sprintf("<script>%s</script>", $content);
    }

    private function makeUniqueString($jsFilePath)
    {
        return bin2hex($jsFilePath);
    }
}
