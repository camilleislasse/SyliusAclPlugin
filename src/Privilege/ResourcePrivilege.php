<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Privilege;

use BitBag\SyliusAclPlugin\Exception\PrivilegeArgumentNotImplementedException;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

final class ResourcePrivilege implements PrivilegeInterface
{
    /** @var Inflector|null */
    private $inflectorInstance;

    public function __construct(
        private RouterInterface $router,
        private array $permissions,
    ) {
    }

    public function getSupportedRouteMap(array $currentRouteMap = [], bool $isTree = false): array
    {
        foreach ($this->router->getRouteCollection() as $routeName => $route) {
            if (!$this->isSyliusResource($route)) {
                continue;
            }

            $default = $route->getDefault('_sylius');

            if (!(isset($default['permission']) && true === $default['permission'])) {
                continue;
            }

            if (false !== stristr($route->getDefault('_controller'), '\\')) {
                continue;
            }

            if ($this->isAdminSection($default) ||
                $this->isAdminPartial($routeName) ||
                $this->isAdminAjax($routeName) ||
                $this->isStateMachineAction($route)
            ) {
                $currentRouteMap = $this->pushRoute($routeName, $currentRouteMap, $route, $isTree);
            }
        }

        $currentRouteMap = $this->addCustomPermissions($currentRouteMap, $isTree);

        ksort($currentRouteMap);

        return $currentRouteMap;
    }

    private function addCustomPermissions(array $currentRouteMap, bool $isTree = false): array
    {
        $customPermissions = $this->getCustomPermissionsWithRouteCollection($currentRouteMap, $isTree);

        foreach ($this->permissions as $permission => $config) {
            if (!$this->hasPermission($permission, $currentRouteMap, $isTree)) {
                $customPermissions[$permission] = $config;
            }
        }

        foreach ($customPermissions as $customPermission => $config) {
            if (isset($config['enabled']) && !$config['enabled']) {
                continue;
            }

            if (!isset($config['label'])) {
                throw new PrivilegeArgumentNotImplementedException($customPermission, 'label');
            }

            if (!$isTree) {
                $currentRouteMap[$customPermission] = $config['label'];

                continue;
            }

            if (!isset($config['parent'])) {
                throw new PrivilegeArgumentNotImplementedException($customPermission, 'parent');
            }

            if (!isset($currentRouteMap[$config['parent']])) {
                $currentRouteMap[$config['parent']] = [];
            }

            $currentRouteMap[$config['parent']][$customPermission] = $config['label'];
        }

        return $currentRouteMap;
    }

    private function getCustomPermissionsWithRouteCollection(array $currentRouteMap, bool $isTree): array
    {
        $customPermissions = [];

        /** @var Route $route */
        foreach ($this->router->getRouteCollection() as $key => $route) {
            if (!$route->hasDefault('_bitbag_sylius_acl_plugin') || $this->hasPermission($key, $currentRouteMap, $isTree)) {
                continue;
            }

            $config = $route->getDefault('_bitbag_sylius_acl_plugin');

            $customPermissions[$key] = $config;
        }

        return $customPermissions;
    }

    private function pushRoute(
        string $routeName,
        array $currentRouteMap,
        Route $route,
        bool $isTree = false,
    ): array {
        if (isset($this->permissions[$routeName]['enabled']) && !(bool) $this->permissions[$routeName]['enabled']) {
            return $currentRouteMap;
        }

        if (
            null !== $this->getRouteParameterCustom($route, 'enabled') &&
            false === (bool) $this->getRouteParameterCustom($route, 'enabled')
        ) {
            return $currentRouteMap;
        }

        $aliasName = $this->getAliasName($route->getDefault('_controller'));

        if (!$isTree) {
            $currentRouteMap[$routeName] = $this->getRouteLabel($routeName, $aliasName, $route);

            return $currentRouteMap;
        }

        $parent = $this->getInflector()->pluralize($aliasName);

        if (null !== $this->getRouteParent($route, $routeName)) {
            $parent = $this->getRouteParent($route, $routeName);
        }

        if (!isset($currentRouteMap[$parent])) {
            $currentRouteMap[$parent] = [];
        }

        $currentRouteMap[$parent][$routeName] = $this->getRouteLabel($routeName, $aliasName, $route);

        return $currentRouteMap;
    }

    private function hasPermission(
        string $permission,
        array $currentRouteMap,
        bool $isTree = false,
    ): bool {
        if (!$isTree) {
            return array_key_exists($permission, $currentRouteMap);
        }

        foreach ($currentRouteMap as $item) {
            if (array_key_exists($permission, $item)) {
                return true;
            }
        }

        return false;
    }

    private function getRouteParent(Route $route, string $routeName): ?string
    {
        if (isset($this->permissions[$routeName]['parent'])) {
            $parent = $this->permissions[$routeName]['parent'] ?? null;
            if (null !== $parent) {
                return $parent;
            }
        }

        if (null !== $parent = $this->getRouteParameterCustom($route, 'parent')) {
            return $parent;
        }

        return null;
    }

    private function getRouteLabel(
        string $routeName,
        string $aliasName,
        Route $route,
    ): ?string {
        if (array_key_exists($routeName, $this->permissions) && null !== $this->permissions[$routeName]['label']) {
            return $this->permissions[$routeName]['label'];
        }

        if (null !== $label = $this->getRouteParameterCustom($route, 'label')) {
            return $label;
        }

        if (true === $this->isStateMachineAction($route)) {
            $stateMachine = $route->getDefault('_sylius')['state_machine'];

            return sprintf('action.%s_%s', $aliasName, $stateMachine['transition']);
        }

        if ($this->isAdminPartial($routeName)) {
            return preg_replace('/\w{0,}(admin_partial)_/', 'partial.', $routeName);
        }

        if ($this->isAdminAjax($routeName)) {
            return preg_replace('/\w{0,}(admin_ajax)_/', 'ajax.', $routeName);
        }

        return preg_replace('/\w{0,}(' . $aliasName . ')_/', 'action.', $routeName);
    }

    private function getRouteParameterCustom(Route $route, string $key): mixed
    {
        if (null !== $route->getDefault('_bitbag_sylius_acl_plugin') &&
            isset($route->getDefault('_bitbag_sylius_acl_plugin')[$key])
        ) {
            return $route->getDefault('_bitbag_sylius_acl_plugin')[$key];
        }

        return null;
    }

    private function getAliasName(string $controller): string
    {
        return explode(':', explode('.', $controller)[2])[0];
    }

    private function isAdminSection(array $syliusDefaults): bool
    {
        return isset($syliusDefaults['section']) && 'admin' === $syliusDefaults['section'];
    }

    private function isAdminPartial(string $routeName): bool
    {
        return false !== strstr($routeName, 'admin_partial_');
    }

    private function isAdminAjax(string $routeName): bool
    {
        return false !== strstr($routeName, 'admin_ajax_');
    }

    private function isStateMachineAction(Route $route): bool
    {
        if (!$this->isSyliusResource($route)) {
            return false;
        }

        $default = $route->getDefault('_sylius');

        if (true === isset($default['state_machine'])) {
            return true;
        }

        return false;
    }

    private function isSyliusResource(Route $route): bool
    {
        return $route->hasDefault('_sylius');
    }

    private function getInflector(): Inflector
    {
        if (null === $this->inflectorInstance) {
            $inflectorFactory = InflectorFactory::create();

            $this->inflectorInstance = $inflectorFactory->build();
        }

        return $this->inflectorInstance;
    }
}
