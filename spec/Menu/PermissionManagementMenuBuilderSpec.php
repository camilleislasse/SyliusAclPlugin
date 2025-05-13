<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Menu;

use BitBag\SyliusAclPlugin\Menu\PermissionManagementMenuBuilder;
use Knp\Menu\ItemInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class PermissionManagementMenuBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PermissionManagementMenuBuilder::class);
    }

    public function it_builds_menu(
        MenuBuilderEvent $menuBuilderEvent,
        ItemInterface $item
    ): void {
        $menuBuilderEvent->getMenu()->willReturn($item);
        $item->addChild('bitbag_acl')->willReturn($item);
        $item->setLabel('bitbag_sylius_acl_plugin.ui.permission_management')->willReturn($item);
        $item->addChild('roles', ['route' => 'bitbag_sylius_acl_plugin_admin_role_index'])->willReturn($item);
        $item->setLabel('bitbag_sylius_acl_plugin.ui.roles')->willReturn($item);
        $item->setLabelAttribute('icon', 'key icon')->willReturn($item);

        $this->buildMenu($menuBuilderEvent);
    }
}
