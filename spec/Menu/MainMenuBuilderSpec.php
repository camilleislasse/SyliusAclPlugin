<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Menu;

use BitBag\SyliusAclPlugin\Menu\AbstractPermissionChecker;
use BitBag\SyliusAclPlugin\Menu\MainMenuBuilder;
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Knp\Menu\MenuItem;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class MainMenuBuilderSpec extends ObjectBehavior
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
        $this->shouldHaveType(MainMenuBuilder::class);
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
        MenuItem $thirdItem,
        MenuItem $fourthItem,
        MenuItem $fifthItem,
        AdminPermissionResolverInterface $adminPermissionResolver,
        PrivilegeInterface $compositePrivilege
    ): void {
        $thirdItem->getExtras()->willReturn(['routes' => [['route' => 'update']]]);
        $thirdItem->getChildren()->willReturn([]);
        $thirdItem->getName()->willReturn('Update');
        $fourthItem->getExtras()->willReturn(['routes' => [['route' => 'create']]]);
        $fourthItem->getChildren()->willReturn([]);
        $fourthItem->getName()->willReturn('Create');
        $firstItem->getName()->willReturn('Products');
        $firstItem->getChildren()->willReturn([$thirdItem]);
        $firstItem->getExtras()->willReturn([]);
        $secondItem->getName()->willReturn('Orders');
        $secondItem->getChildren()->willReturn([$fourthItem]);
        $secondItem->getExtras()->willReturn([]);
        $fifthItem->getName()->willReturn('Roles');
        $fifthItem->getChildren()->willReturn([]);
        $fifthItem->getExtras()->willReturn([]);
        $menuItem->getChildren()->willReturn([$firstItem, $secondItem, $fifthItem]);
        $menuBuilderEvent->getMenu()->willReturn($menuItem);
        $adminPermissionResolver->resolvePermission('update')->willReturn(true);
        $adminPermissionResolver->resolvePermission('create')->willReturn(false);
        $compositePrivilege->getSupportedRouteMap()->willReturn(['_index']);

        $secondItem->removeChild('Create')->shouldBeCalled();
        $menuItem->removeChild('Roles')->shouldBeCalled();

        $this->buildMenu($menuBuilderEvent);
    }
}
