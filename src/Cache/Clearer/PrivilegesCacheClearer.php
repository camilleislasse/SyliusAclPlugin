<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Cache\Clearer;

use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

final class PrivilegesCacheClearer implements CacheClearerInterface
{
    public function __construct(
        private FilesystemAdapter $filesystemCache,
    ) {
    }

    public function clear(string $cacheDir): void
    {
        $this->filesystemCache->deleteItems(PrivilegeInterface::CACHE_CLEARER_KEYS);
    }
}
