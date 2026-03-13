<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Menu;

use Knp\Menu\MenuItem;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class PermissionManagementMenuBuilder
{
    public function buildMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        /** @var MenuItem $menu */
        $menu = $menuBuilderEvent->getMenu();

        $aclRootMenuItem = $menu
            ->addChild('bitbag_acl')
            ->setLabel('bitbag_sylius_acl_plugin.ui.permission_management')
        ;
        $aclRootMenuItem
            ->addChild('roles', [
                'route' => 'bitbag_sylius_acl_plugin_admin_role_index',
            ])
            ->setLabel('bitbag_sylius_acl_plugin.ui.roles')
            ->setLabelAttribute('icon', 'tabler:key')
        ;
    }
}
