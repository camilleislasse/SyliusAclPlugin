<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\EventListener;

use BitBag\SyliusAclPlugin\EventListener\PermissionCheckerListener;
use BitBag\SyliusAclPlugin\Exception\AccessDeniedHttpException;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class PermissionCheckerListenerSpec extends ObjectBehavior
{
    public function let(AdminPermissionResolverInterface $adminPermissionResolver): void
    {
        $this->beConstructedWith($adminPermissionResolver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PermissionCheckerListener::class);
    }

    public function it_does_not_throw_exception_if_route_is_null(
        RequestEvent $event,
        Request $request
    ): void {
        $request->get('_route')->willReturn(null);
        $event->getRequest()->willReturn($request);

        $this->onKernelRequest($event);
    }

    public function it_does_not_throw_exception_if_has_permission(
        RequestEvent $event,
        Request $request,
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $request->get('_route')->willReturn('create');
        $event->getRequest()->willReturn($request);
        $adminPermissionResolver->resolvePermission('create')->willReturn(true);

        $this->onKernelRequest($event);
    }

    public function it_throw_not_found_exception_if_has_permission(
        RequestEvent $event,
        Request $request,
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $request->get('_route')->willReturn('create');
        $event->getRequest()->willReturn($request);
        $adminPermissionResolver->resolvePermission('create')->willReturn(false);

        $this->shouldThrow(
            new AccessDeniedHttpException('create')
        )->during('onKernelRequest', [$event]);
    }
}
