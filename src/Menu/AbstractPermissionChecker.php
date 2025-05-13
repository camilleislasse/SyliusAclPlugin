<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Menu;

use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Knp\Menu\MenuItem;

abstract class AbstractPermissionChecker
{
    private const EXCEPTION_ROUTES = [
        'taxons' => 'sylius_admin_taxon_create',
    ];

    public function __construct(
        protected AdminPermissionResolverInterface $adminPermissionResolver,
        protected PrivilegeInterface $compositePrivilege,
    ) {
    }

    protected function removeMenuItemWithoutPermission(MenuItem $parent): void
    {
        /** @var MenuItem $child */
        foreach ($parent->getChildren() as $child) {
            $extras = $child->getExtras();

            if (true === isset($extras['routes'])) {
                $toRemove = false;
                foreach ($extras['routes'] as $route) {
                    if (false === $this->adminPermissionResolver->resolvePermission($route['route'])) {
                        $toRemove = true;
                    }
                }

                $hasIndexPermission = $this->hasIndexPermission($child->getName(), $extras);

                if ($toRemove && false === $hasIndexPermission) {
                    $parent->removeChild($child->getName());
                }
            }

            $this->removeMenuItemWithoutPermission($child);
        }
    }

    private function hasIndexPermission(string $nodeName, array $extras): bool
    {
        if (0 === count($extras['routes'])) {
            return false;
        }

        if (isset(self::EXCEPTION_ROUTES[$nodeName])) {
            return $this->adminPermissionResolver->resolvePermission(self::EXCEPTION_ROUTES[$nodeName]);
        }

        $firstRoute = $extras['routes'][0]['route'];
        $explodedRoute = explode('_', $firstRoute);
        array_pop($explodedRoute);
        $indexRoute = implode('_', $explodedRoute) . '_index';

        if (false === $this->permissionExists($indexRoute)) {
            return false;
        }

        return $this->adminPermissionResolver->resolvePermission($indexRoute);
    }

    private function permissionExists(string $permission): bool
    {
        $supportedRouteMap = $this->compositePrivilege->getSupportedRouteMap();

        return array_key_exists($permission, $supportedRouteMap);
    }

    protected function menuCleanup(MenuItem $parent): void
    {
        /** @var MenuItem $child */
        foreach ($parent->getChildren() as $child) {
            if (0 === count($child->getChildren())) {
                $parent->removeChild($child->getName());
            }
        }
    }
}
