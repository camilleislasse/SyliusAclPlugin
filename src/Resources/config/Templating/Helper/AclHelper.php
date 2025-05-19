<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Templating\Helper;

use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Symfony\Component\Templating\Helper\Helper;

class AclHelper extends Helper
{
    public function __construct(
        protected AdminPermissionResolverInterface $adminPermissionResolver,
    ) {
    }

    public function getAvailableGridActions(
        ResourceGridView $resourceGridView,
        array $actions,
        bool $bulkAction = false,
    ): array {
        $requestConfiguration = $resourceGridView->getRequestConfiguration();

        /** @var Action $action */
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

    public function getName(): string
    {
        return 'bitbag_acl';
    }
}
