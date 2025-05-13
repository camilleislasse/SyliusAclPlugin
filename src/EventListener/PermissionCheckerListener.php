<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\EventListener;

use BitBag\SyliusAclPlugin\Exception\AccessDeniedHttpException;
use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class PermissionCheckerListener
{
    public function __construct(
        private AdminPermissionResolverInterface $adminPermissionResolver,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $route = $event->getRequest()->get('_route');

        if (null !== $route && !$this->adminPermissionResolver->resolvePermission($route)) {
            throw new AccessDeniedHttpException($route);
        }
    }
}
