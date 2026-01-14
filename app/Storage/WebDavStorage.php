<?php

namespace App\Storage;

use App\Contracts\StorageInterface;
use Illuminate\Support\Facades\Storage;

class WebDavStorage implements StorageInterface
{
    public function listFiles(string $directory = '/'): array
    {
        $disk = Storage::disk('webdav');

        return collect($disk->listContents($directory, false))
            ->filter(fn ($item) => ($item['type'] ?? '') === 'file')
            ->map(fn ($item) => [
                'path' => $item['path'] ?? '',
                'basename' => basename($item['path'] ?? ''),
                'size' => $item['size'] ?? 0,
                'last_modified' => $item['last_modified'] ?? null,
            ])
            ->values()
            ->all();
    }

    public function storeFiles(array $files, string $directory = '/'): void
    {
        $disk = Storage::disk('webdav');

        foreach ($files as $file) {
            $disk->putFileAs($directory, $file, $file->getClientOriginalName());
        }
    }

    public function exists(string $path): bool
    {
        return Storage::disk('webdav')->exists($path);
    }

    public function readStream(string $path)
    {
        return Storage::disk('webdav')->readStream($path);
    }
}
