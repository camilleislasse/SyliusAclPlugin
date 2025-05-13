<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Checker;

use BitBag\SyliusAclPlugin\Checker\AuthorizationCheckerInterface;
use BitBag\SyliusAclPlugin\Checker\PermissionChecker;
use BitBag\SyliusAclPlugin\Context\AdminUserContextInterface;
use BitBag\SyliusAclPlugin\Entity\RoleInterface;
use BitBag\SyliusAclPlugin\Model\AdminUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;

final class PermissionCheckerSpec extends ObjectBehavior
{
    public function let(AdminUserContextInterface $adminUserContext): void
    {
        $this->beConstructedWith($adminUserContext);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PermissionChecker::class);
    }

    public function it_implements_authorization_checker_interface(): void
    {
        $this->shouldHaveType(AuthorizationCheckerInterface::class);
    }

    public function it_returns_true_if_admin_user_has_permission(
        AdminUserContextInterface $adminUserContext,
        AdminUserInterface $adminUser,
        RoleInterface $role
    ): void {
        $role->hasPermission('create')->willReturn(true);
        $roles = new ArrayCollection([
            $role->getWrappedObject(),
        ]);
        $adminUser->getRolesResources()->willReturn($roles);
        $adminUserContext->getAdminUser()->willReturn($adminUser);

        $this->isGranted('create')->shouldReturn(true);
    }

    public function it_returns_false_if_admin_user_not_has_permission(
        AdminUserContextInterface $adminUserContext,
        AdminUserInterface $adminUser,
        RoleInterface $role
    ): void {
        $role->hasPermission('create')->willReturn(false);
        $roles = new ArrayCollection([
            $role->getWrappedObject(),
        ]);
        $adminUser->getRolesResources()->willReturn($roles);
        $adminUserContext->getAdminUser()->willReturn($adminUser);

        $this->isGranted('create')->shouldReturn(false);
    }

    public function it_returns_false_if_admin_user_is_null(
        AdminUserContextInterface $adminUserContext
    ): void {
        $adminUserContext->getAdminUser()->willReturn(null);

        $this->isGranted('create')->shouldReturn(false);
    }

    public function it_returns_false_if_admin_user_not_has_roles(
        AdminUserContextInterface $adminUserContext,
        AdminUserInterface $adminUser
    ): void {
        $roles = new ArrayCollection([]);
        $adminUser->getRolesResources()->willReturn($roles);
        $adminUserContext->getAdminUser()->willReturn($adminUser);

        $this->isGranted('create')->shouldReturn(false);
    }
}
