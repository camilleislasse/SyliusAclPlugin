<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Checker;

use BitBag\SyliusAclPlugin\Context\AdminUserContextInterface;
use BitBag\SyliusAclPlugin\Entity\RoleInterface;

final class PermissionChecker implements AuthorizationCheckerInterface
{
    public function __construct(
        private AdminUserContextInterface $adminUserContext,
    ) {
    }

    public function isGranted(string $permission): bool
    {
        $adminUser = $this->adminUserContext->getAdminUser();

        if (null === $adminUser) {
            return false;
        }

        /** @var RoleInterface $rolesResource */
        foreach ($adminUser->getRolesResources() as $rolesResource) {
            if (true === $rolesResource->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
