<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Checker;

use BitBag\SyliusAclPlugin\Checker\ResourceAuthorizationChecker;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Component\HttpFoundation\Request;

final class ResourceAuthorizationCheckerSpec extends ObjectBehavior
{
    public function let(AdminPermissionResolverInterface $adminPermissionResolver): void
    {
        $this->beConstructedWith($adminPermissionResolver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourceAuthorizationChecker::class);
    }

    public function it_implements_authorization_checker_interface(): void
    {
        $this->shouldHaveType(AuthorizationCheckerInterface::class);
    }

    public function it_returns_true_if_has_permission(
        RequestConfiguration $requestConfiguration,
        AdminPermissionResolverInterface $adminPermissionResolver,
        Request $request
    ): void {
        $adminPermissionResolver->resolvePermission('create')->willReturn(true);
        $request->get('_route')->willReturn('create');
        $requestConfiguration->getRequest()->willReturn($request);

        $this->isGranted($requestConfiguration, 'create')->shouldReturn(true);
    }

    public function it_returns_false_if_not_has_permission(
        RequestConfiguration $requestConfiguration,
        AdminPermissionResolverInterface $adminPermissionResolver,
        Request $request
    ): void {
        $adminPermissionResolver->resolvePermission('create')->willReturn(false);
        $request->get('_route')->willReturn('create');
        $requestConfiguration->getRequest()->willReturn($request);

        $this->isGranted($requestConfiguration, 'create')->shouldReturn(false);
    }
}
