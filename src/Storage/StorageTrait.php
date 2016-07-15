<?php

namespace Kreait\Firebase\Storage;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\Handler;
use League\Flysystem\PluginInterface;

trait StorageTrait
{
    /**
     * @var FilesystemInterface
     */
    protected $fs;

    public function has($path)
    {
        return $this->fs->has($path);
    }

    public function read($path)
    {
        return $this->fs->read($path);
    }

    public function readStream($path)
    {
        return $this->fs->readStream($path);
    }

    public function listContents($directory = '', $recursive = false)
    {
        return $this->fs->listContents($directory, $recursive);
    }

    public function getMetadata($path)
    {
        return $this->fs->getMetadata($path);
    }

    public function getSize($path)
    {
        return $this->getSize($path);
    }

    public function getMimetype($path)
    {
        return $this->fs->getMimetype($path);
    }

    public function getTimestamp($path)
    {
        return $this->fs->getTimestamp($path);
    }

    public function getVisibility($path)
    {
        return $this->fs->getVisibility($path);
    }

    public function write($path, $contents, array $config = [])
    {
        return $this->fs->write($path, $contents, $config);
    }

    public function writeStream($path, $resource, array $config = [])
    {
        return $this->fs->writeStream($path, $resource, $config);
    }

    public function update($path, $contents, array $config = [])
    {
        return $this->fs->update($path, $contents, $config);
    }

    public function updateStream($path, $resource, array $config = [])
    {
        return $this->fs->updateStream($path, $resource, $config);
    }

    public function rename($path, $newpath)
    {
        return $this->fs->rename($path, $newpath);
    }

    public function copy($path, $newpath)
    {
        return $this->fs->copy($path, $newpath);
    }

    public function delete($path)
    {
        return $this->fs->delete($path);
    }

    public function deleteDir($dirname)
    {
        return $this->fs->deleteDir($dirname);
    }

    public function createDir($dirname, array $config = [])
    {
        $this->fs->createDir($dirname, $config);
    }

    public function setVisibility($path, $visibility)
    {
        $this->fs->setVisibility($path, $visibility);
    }

    public function put($path, $contents, array $config = [])
    {
        return $this->fs->put($path, $contents, $config);
    }

    public function putStream($path, $resource, array $config = [])
    {
        return $this->fs->putStream($path, $resource, $config);
    }

    public function readAndDelete($path)
    {
        return $this->fs->readAndDelete($path);
    }

    public function get($path, Handler $handler = null)
    {
        return $this->fs->get($path, $handler);
    }

    public function addPlugin(PluginInterface $plugin)
    {
        return $this->fs->addPlugin($plugin);
    }
}
