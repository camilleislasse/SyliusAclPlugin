<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Privilege;

use Laminas\Stdlib\PriorityQueue;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class CompositePrivilege implements PrivilegeInterface
{
    /** @var PriorityQueue|PrivilegeInterface[] */
    private $privileges;

    public function __construct(
        private FilesystemAdapter $filesystemCache,
    ) {
        $this->privileges = new PriorityQueue();
    }

    public function addPrivilege(PrivilegeInterface $privilege, int $priority = 0): void
    {
        $this->privileges->insert($privilege, $priority);
    }

    public function getSupportedRouteMap(array $currentRouteMap = [], bool $isTree = false): array
    {
        $cacheKey = true === $isTree ? self::PERMISSIONS_TREE_CACHE_KEY : self::PERMISSIONS_MAP_CACHE_KEY;

        return $this->filesystemCache->get($cacheKey, function () use ($currentRouteMap, $isTree) {
            foreach ($this->privileges as $privilege) {
                $currentRouteMap = $privilege->getSupportedRouteMap($currentRouteMap, $isTree);
            }

            return $currentRouteMap;
        });
    }
}
