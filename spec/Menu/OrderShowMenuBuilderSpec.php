<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Menu;

use BitBag\SyliusAclPlugin\Menu\AbstractPermissionChecker;
use BitBag\SyliusAclPlugin\Menu\OrderShowMenuBuilder;
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Knp\Menu\MenuItem;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class OrderShowMenuBuilderSpec extends ObjectBehavior
{
    public function let(
        AdminPermissionResolverInterface $adminPermissionResolver,
        PrivilegeInterface $compositePrivilege
    ): void {
        $this->beConstructedWith(
            $adminPermissionResolver,
            $compositePrivilege
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderShowMenuBuilder::class);
    }

    public function it_extends_abstract_permission_checker(): void
    {
        $this->shouldHaveType(AbstractPermissionChecker::class);
    }

    public function it_removes_menu_item_without_permission(
        MenuBuilderEvent $menuBuilderEvent,
        MenuItem $menuItem,
        MenuItem $firstItem,
        MenuItem $secondItem,
        AdminPermissionResolverInterface $adminPermissionResolver,
        PrivilegeInterface $compositePrivilege
    ): void {
        $firstItem->getExtras()->willReturn(['routes' => [['route' => 'update']]]);
        $firstItem->getChildren()->willReturn([]);
        $secondItem->getExtras()->willReturn(['routes' => [['route' => 'create']]]);
        $secondItem->getChildren()->willReturn([]);
        $firstItem->getName()->willReturn('Update');
        $secondItem->getName()->willReturn('Create');
        $menuItem->getChildren()->willReturn([$firstItem, $secondItem]);
        $menuBuilderEvent->getMenu()->willReturn($menuItem);
        $adminPermissionResolver->resolvePermission('update')->willReturn(true);
        $adminPermissionResolver->resolvePermission('create')->willReturn(false);
        $compositePrivilege->getSupportedRouteMap()->willReturn(['_index']);

        $menuItem->removeChild('Create')->shouldBeCalled();

        $this->buildMenu($menuBuilderEvent);
    }
}
