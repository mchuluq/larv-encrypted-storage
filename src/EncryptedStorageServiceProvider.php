<?php

namespace Mchuluq\Larv\EncryptedStorage;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem as Flysystem;
use Mchuluq\Larv\EncryptedStorage\CipherMethods\CipherMethodFactory;
use Mchuluq\Larv\EncryptedStorage\Exceptions\InvalidConfiguration;
use Mchuluq\Larv\EncryptedStorage\FilesystemAdapters\EncryptedLocalAdapter;
use Mchuluq\Larv\EncryptedStorage\FilesystemAdapters\FilesystemAdapter;

class EncryptedStorageServiceProvider extends ServiceProvider
{
    public function boot(FilesystemManager $filesystemManager)
    {
        $filesystemManager->extend('encrypted-storage', function ($app, $config) use ($filesystemManager) {
            $this->validateConfiguration($config);
            $cipherMethod = CipherMethodFactory::make($config);

            $permissions = $config['permissions'] ?? [];

            $links = ($config['links'] ?? null) === 'skip'
                ? EncryptedLocalAdapter::SKIP_LINKS
                : EncryptedLocalAdapter::DISALLOW_LINKS;

            $adapter = new EncryptedLocalAdapter($cipherMethod, $config['root'], $config['lock'] ?? LOCK_EX, $links, $permissions);

            return new FilesystemAdapter(new Flysystem($adapter, count($config) > 0 ? $config : null));
        });
    }

    protected function validateConfiguration(array $config)
    {
        $requiredKeys = ['key', 'cipher-method', 'root'];

        foreach ($requiredKeys as $key) {
            if (empty($config[$key])) {
                throw new InvalidConfiguration($key);
            }
        }

    }
}