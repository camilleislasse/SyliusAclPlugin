<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Privilege;

interface PrivilegeInterface
{
    public const PERMISSIONS_MAP_CACHE_KEY = 'bitbag_sylius_acl_plugin.cache.permissions_map';

    public const PERMISSIONS_TREE_CACHE_KEY = 'bitbag_sylius_acl_plugin.cache.permissions_tree';

    public const CACHE_CLEARER_KEYS = [
        self::PERMISSIONS_TREE_CACHE_KEY,
        self::PERMISSIONS_MAP_CACHE_KEY,
    ];

    public function getSupportedRouteMap(array $currentRouteMap = [], bool $isTree = false): array;
}
