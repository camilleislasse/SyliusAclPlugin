<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Resolver;

use BitBag\SyliusAclPlugin\Checker\AuthorizationCheckerInterface;
use BitBag\SyliusAclPlugin\Context\AdminUserContextInterface;
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class AdminPermissionResolver implements AdminPermissionResolverInterface
{
    public function __construct(
        private AuthorizationCheckerInterface $compositeAuthorizationChecker,
        private PrivilegeInterface $compositePrivilege,
        private AdminUserContextInterface $adminUserContext,
        private RequestMatcherInterface $adminRequestMatcher,
        private RequestStack $requestStack,
    ) {
    }

    public function resolvePermission(string $permission): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return true;
        }

        if (!$this->adminRequestMatcher->matches($request)) {
            return true;
        }

        $supportedRouteMap = $this->compositePrivilege->getSupportedRouteMap();

        if (!array_key_exists($permission, $supportedRouteMap)) {
            return true;
        }

        $adminUser = $this->adminUserContext->getAdminUser();

        if (null === $adminUser) {
            return false;
        }

        if (!$adminUser->isEnablePermissionChecker()) {
            return true;
        }

        if (true === $this->compositeAuthorizationChecker->isGranted($permission)) {
            return true;
        }

        return false;
    }
}
