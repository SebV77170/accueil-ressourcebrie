<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client as WebDAVClient;
use Illuminate\Filesystem\FilesystemAdapter;

class WebdavStorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('webdav', function ($app, $config) {

            $client = new WebDAVClient([
                'baseUri'  => rtrim($config['base_uri'], '/') . '/',
                'userName' => $config['username'],
                'password' => $config['password'],
            ]);

            $adapter = new WebDAVAdapter(
                $client,
                $config['root'] ?? '/'
            );

            return new FilesystemAdapter(
                new Filesystem($adapter),
                $adapter,
                $config
            );
        });
    }
}
