<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface StorageInterface
{
    public function listFiles(string $directory = '/'): array;

    /**
     * @param UploadedFile[] $files
     */
    public function storeFiles(array $files, string $directory = '/'): void;

    public function exists(string $path): bool;

    public function readStream(string $path);
}
