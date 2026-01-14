<?php

namespace App\Services\Files;

use DomainException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;

class WebdavFileService
{
    public function list(string $path): array
    {
        $path = $this->sanitizePath($path);

        if ($path !== '' && ! $this->disk()->directoryExists($path)) {
            throw new DomainException('Dossier introuvable.');
        }

        $items = [];

        foreach ($this->disk()->listContents($path, false) as $item) {
            $items[] = $this->formatItem($item);
        }

        usort($items, function (array $first, array $second) {
            if ($first['type'] !== $second['type']) {
                return $first['type'] === 'dir' ? -1 : 1;
            }

            return strcasecmp($first['name'], $second['name']);
        });

        return $items;
    }

    public function download(string $path): array
    {
        $path = $this->sanitizePath($path);

        if ($path === '' || ! $this->disk()->exists($path)) {
            throw new DomainException('Fichier introuvable.');
        }

        $stream = $this->disk()->readStream($path);

        if (! is_resource($stream)) {
            throw new DomainException('Impossible de lire le fichier.');
        }

        return [
            'stream' => $stream,
            'name' => basename($path),
            'mime' => $this->disk()->mimeType($path) ?? 'application/octet-stream',
        ];
    }

    public function upload(string $path, string $name, $file): void
    {
        $path = $this->sanitizePath($path);
        $name = $this->sanitizeFilename($name);

        if ($name === '') {
            throw new DomainException('Nom de fichier invalide.');
        }

        $this->disk()->putFileAs($path, $file, $name);
    }

    public function createDirectory(string $path, string $name): void
    {
        $path = $this->sanitizePath($path);
        $name = $this->sanitizeFilename($name);

        if ($name === '') {
            throw new DomainException('Nom de dossier invalide.');
        }

        $target = $path === '' ? $name : "{$path}/{$name}";
        $this->disk()->makeDirectory($target);
    }

    public function delete(string $path): void
    {
        $path = $this->sanitizePath($path);

        if ($path === '') {
            throw new DomainException('Impossible de supprimer la racine.');
        }

        if (! $this->disk()->exists($path)) {
            throw new DomainException('Élément introuvable.');
        }

        if ($this->disk()->directoryExists($path)) {
            $this->disk()->deleteDirectory($path);
            return;
        }

        $this->disk()->delete($path);
    }

    public function move(string $from, string $to): void
    {
        $from = $this->sanitizePath($from);
        $to = $this->sanitizePath($to);

        if ($from === '' || $to === '') {
            throw new DomainException('Chemin invalide.');
        }

        $this->disk()->move($from, $to);
    }

    private function disk(): FilesystemAdapter
    {
        return Storage::disk('webdav');
    }

    private function sanitizePath(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '' || $path === '/') {
            return '';
        }

        $path = trim($path, '/');
        $segments = array_filter(explode('/', $path), 'strlen');

        foreach ($segments as $segment) {
            if ($segment === '..' || str_contains($segment, "\0")) {
                throw new DomainException('Chemin invalide.');
            }
        }

        return implode('/', $segments);
    }

    private function sanitizeFilename(string $name): string
    {
        $name = trim($name);

        if ($name === '') {
            return '';
        }

        $name = basename(str_replace('\\', '/', $name));

        if ($name === '.' || $name === '..') {
            return '';
        }

        return $name;
    }

    private function formatItem(StorageAttributes $item): array
    {
        $path = $item->path();

        return [
            'name' => basename($path),
            'path' => $path,
            'type' => $item->type(),
            'size' => $item instanceof FileAttributes ? $item->fileSize() : null,
            'last_modified' => $item instanceof FileAttributes ? $item->lastModified() : null,
        ];
    }
}
