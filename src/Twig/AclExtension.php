<?php

namespace BitBag\SyliusAclPlugin\Twig;

use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AclExtension extends AbstractExtension
{
    public function __construct(
        private AdminPermissionResolverInterface $adminPermissionResolver,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_acl_get_available_grid_actions', [$this, 'getAvailableGridActions']),
            new TwigFunction('bitbag_acl_has_permission', [$this, 'hasPermission']),
        ];
    }

    public function getAvailableGridActions(
        ResourceGridView $resourceGridView,
        array $actions,
        bool $bulkAction = false,
    ): array {
        $requestConfiguration = $resourceGridView->getRequestConfiguration();

        foreach ($actions as $key => &$action) {
            if ('links' !== $action->getType()) {
                if (!isset($action->getOptions()['link']['route'])) {
                    $name = $bulkAction ? 'bulk_' . $action->getType() : $action->getType();
                    $permission = $requestConfiguration->getRouteName($name);
                } else {
                    $permission = $action->getOptions()['link']['route'];
                }

                if (!$this->adminPermissionResolver->resolvePermission($permission)) {
                    unset($actions[$key]);
                }

                continue;
            }

            $options = $action->getOptions();

            foreach ($options['links'] as $name => $link) {
                if (!isset($link['route'])) {
                    continue;
                }

                if (!$this->adminPermissionResolver->resolvePermission($link['route'])) {
                    unset($options['links'][$name]);
                }
            }

            $action->setOptions($options);

            if (0 === count($options['links'])) {
                unset($actions[$key]);
            }
        }

        return $actions;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->adminPermissionResolver->resolvePermission($permission);
    }
}

