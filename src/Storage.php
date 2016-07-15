<?php

namespace Kreait\Firebase;

use Kreait\Firebase\Storage\StorageTrait;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Plugin;

/**
 * @method array listFiles(string $path = '', bool $recursive = false)
 * @method array listPaths(string $path = '', bool $recursive = false)
 */
final class Storage implements FilesystemInterface
{
    use StorageTrait;

    public function __construct(FilesystemInterface $filesystem)
    {
        $filesystem->addPlugin(new Plugin\ListFiles());
        $filesystem->addPlugin(new Plugin\ListPaths());

        $this->fs = $filesystem;
    }

    public function __call($method, array $arguments)
    {
        try {
            return call_user_func_array([$this->fs, $method], $arguments);
        } catch (\Throwable $e) {
            throw new Exception\Storage($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function emptyDir(string $path)
    {
        foreach ($this->listFiles($path, true) as $file) {
            $this->delete($file['path']);
        }
    }
}
