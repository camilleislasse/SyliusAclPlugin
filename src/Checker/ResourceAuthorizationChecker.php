<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Checker;

use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface as ResourceAuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

final class ResourceAuthorizationChecker implements ResourceAuthorizationCheckerInterface
{
    public function __construct(
        private AdminPermissionResolverInterface $adminPermissionResolver,
    ) {
    }

    public function isGranted(RequestConfiguration $configuration, string $permission): bool
    {
        $routeName = $configuration->getRequest()->get('_route');
        if (null === $routeName || '' === $routeName) {
            return true;
        }

        return $this->adminPermissionResolver->resolvePermission($routeName);
    }
}
