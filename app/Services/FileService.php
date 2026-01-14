<?php

namespace App\Services;

use App\Contracts\StorageInterface;
use Illuminate\Http\UploadedFile;

class FileService
{
    public function __construct(private StorageInterface $storage)
    {
    }

    public function listFiles(): array
    {
        return $this->storage->listFiles('/');
    }

    /**
     * @param UploadedFile[] $files
     */
    public function upload(array $files): void
    {
        $this->storage->storeFiles($files, '/');
    }

    public function download(string $path)
    {
        $normalizedPath = ltrim($path, '/');

        if ($normalizedPath === '' || !$this->storage->exists($normalizedPath)) {
            return null;
        }

        return $this->storage->readStream($normalizedPath);
    }
}
