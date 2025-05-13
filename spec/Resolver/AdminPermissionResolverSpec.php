<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Resolver;

use BitBag\SyliusAclPlugin\Checker\AuthorizationCheckerInterface;
use BitBag\SyliusAclPlugin\Context\AdminUserContextInterface;
use BitBag\SyliusAclPlugin\Model\AdminUserInterface;
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolver;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class AdminPermissionResolverSpec extends ObjectBehavior
{
    public function let(
        AuthorizationCheckerInterface $compositeAuthorizationChecker,
        PrivilegeInterface $compositePrivilege,
        AdminUserContextInterface $adminUserContext,
        RequestMatcherInterface $adminRequestMatcher,
        RequestStack $requestStack
    ): void {
        $this->beConstructedWith(
            $compositeAuthorizationChecker,
            $compositePrivilege,
            $adminUserContext,
            $adminRequestMatcher,
            $requestStack
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AdminPermissionResolver::class);
    }

    public function it_implements_admin_permission_resolver_interface(): void
    {
        $this->shouldHaveType(AdminPermissionResolverInterface::class);
    }

    public function it_returns_true_if_there_is_no_request(
        RequestStack $requestStack,
    ): void {
        $requestStack->getCurrentRequest()->willReturn(null);

        $this->resolvePermission('create')->shouldReturn(true);
    }

    public function it_returns_true_if_admin_user_has_permission(
        AdminUserContextInterface $adminUserContext,
        RequestMatcherInterface $adminRequestMatcher,
        RequestStack $requestStack,
        Request $request,
        AuthorizationCheckerInterface $compositeAuthorizationChecker,
        PrivilegeInterface $compositePrivilege,
        AdminUserInterface $adminUser
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $adminRequestMatcher->matches($request)->willReturn(true);
        $compositePrivilege->getSupportedRouteMap()->willReturn(['create' => 'action.create']);
        $adminUserContext->getAdminUser()->willReturn($adminUser);
        $adminUser->isEnablePermissionChecker()->willReturn(true);
        $compositeAuthorizationChecker->isGranted('create')->willReturn(true);

        $this->resolvePermission('create')->shouldReturn(true);
    }

    public function it_returns_true_if_route_not_matches(
        RequestMatcherInterface $adminRequestMatcher,
        RequestStack $requestStack,
        Request $request
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $adminRequestMatcher->matches($request)->willReturn(false);

        $this->resolvePermission('create')->shouldReturn(true);
    }

    public function it_returns_true_if_permission_not_supported(
        RequestMatcherInterface $adminRequestMatcher,
        RequestStack $requestStack,
        Request $request,
        PrivilegeInterface $compositePrivilege
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $adminRequestMatcher->matches($request)->willReturn(true);
        $compositePrivilege->getSupportedRouteMap()->willReturn(['update' => 'action.update']);

        $this->resolvePermission('create')->shouldReturn(true);
    }

    public function it_returns_false_if_admin_user_is_null(
        AdminUserContextInterface $adminUserContext,
        RequestMatcherInterface $adminRequestMatcher,
        RequestStack $requestStack,
        Request $request,
        PrivilegeInterface $compositePrivilege
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $adminRequestMatcher->matches($request)->willReturn(true);
        $compositePrivilege->getSupportedRouteMap()->willReturn(['create' => 'action.create']);
        $adminUserContext->getAdminUser()->willReturn(null);

        $this->resolvePermission('create')->shouldReturn(false);
    }

    public function it_returns_true_if_admin_user_has_disabled_permission_checker(
        AdminUserContextInterface $adminUserContext,
        RequestMatcherInterface $adminRequestMatcher,
        RequestStack $requestStack,
        Request $request,
        PrivilegeInterface $compositePrivilege,
        AdminUserInterface $adminUser
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $adminRequestMatcher->matches($request)->willReturn(true);
        $compositePrivilege->getSupportedRouteMap()->willReturn(['create' => 'action.create']);
        $adminUserContext->getAdminUser()->willReturn($adminUser);
        $adminUser->isEnablePermissionChecker()->willReturn(false);

        $this->resolvePermission('create')->shouldReturn(true);
    }

    public function it_returns_true_if_admin_user_not_has_permission(
        AdminUserContextInterface $adminUserContext,
        RequestMatcherInterface $adminRequestMatcher,
        RequestStack $requestStack,
        Request $request,
        AuthorizationCheckerInterface $compositeAuthorizationChecker,
        PrivilegeInterface $compositePrivilege,
        AdminUserInterface $adminUser
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $adminRequestMatcher->matches($request)->willReturn(true);
        $compositePrivilege->getSupportedRouteMap()->willReturn(['create' => 'action.create']);
        $adminUserContext->getAdminUser()->willReturn($adminUser);
        $adminUser->isEnablePermissionChecker()->willReturn(true);
        $compositeAuthorizationChecker->isGranted('create')->willReturn(false);

        $this->resolvePermission('create')->shouldReturn(false);
    }
}
