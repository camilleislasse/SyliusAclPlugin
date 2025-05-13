<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use BitBag\SyliusAclPlugin\Entity\RoleInterface;
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;

final class AdminUserContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var PrivilegeInterface */
    private $privilege;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        UserRepositoryInterface $userRepository,
        PrivilegeInterface $privilege
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->userRepository = $userRepository;
        $this->privilege = $privilege;
    }

    /**
     * @var AdminUserInterface|\BitBag\SyliusAclPlugin\Model\AdminUserInterface
     *
     * @Given /^(this administrator) has permissions check enabled$/
     */
    public function thisAdministratorHasPermissionsCheckEnabled(AdminUserInterface $adminUser): void
    {
        $adminUser->setEnablePermissionChecker(true);

        $this->userRepository->add($adminUser);
    }

    /**
     * @var AdminUserInterface|\BitBag\SyliusAclPlugin\Model\AdminUserInterface
     *
     * @Given /^(this administrator) has permissions check disable$/
     */
    public function thisAdministratorHasPermissionsCheckDisable(AdminUserInterface $adminUser): void
    {
        $adminUser->setEnablePermissionChecker(false);

        $this->userRepository->add($adminUser);
    }

    /**
     * @var AdminUserInterface|\BitBag\SyliusAclPlugin\Model\AdminUserInterface
     *
     * @Given /^(this administrator) has the ("[^"]+" role) with "([^"]+)" permissions for the "([^"]+)"$/
     */
    public function thisAdministrationHasTheRoleWithPermissionsForThe(
        AdminUserInterface $adminUser,
        RoleInterface $role,
        string $permissions,
        string $parent
    ): void {
        $parent = str_replace(' ', '_', strtolower($parent));

        $permissions = explode(',', str_replace(' ', '', strtolower($permissions)));

        $privileges = $this->privilege->getSupportedRouteMap([], true);

        foreach ($privileges[$parent] as $key => $name) {
            foreach ($permissions as $permission) {
                if (stristr($name, '.' . $permission)) {
                    $role->addPermission($key);
                }
            }
        }

        $adminUser->addRoleResource($role);

        $this->userRepository->add($adminUser);
    }
}
