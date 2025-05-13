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
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class RoleContext implements Context
{
    /** @var FactoryInterface */
    private $roleFactory;

    /** @var RepositoryInterface */
    private $roleRepository;

    /** @var PrivilegeInterface */
    private $privilege;

    /** @var \Faker\Generator */
    private $faker;

    public function __construct(
        FactoryInterface $roleFactory,
        RepositoryInterface $roleRepository,
        PrivilegeInterface $privilege
    ) {
        $this->roleFactory = $roleFactory;
        $this->roleRepository = $roleRepository;
        $this->privilege = $privilege;

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @Given there is an existing role with :code code
     */
    public function thereIsARoleWithCode(string $code): void
    {
        $role = $this->createRole($code, $this->faker->text(15));

        $this->saveRole($role);
    }

    /**
     * @Given there is a role :name
     */
    public function thereIsARole(string $name): void
    {
        $role = $this->createRole(uniqid(), $name);

        $this->saveRole($role);
    }

    /**
     * @Given there is a role :name with all permissions for :arg2 resource
     */
    public function thereIsARoleWithAllPermissionsForResource(string $name, string $permission): void
    {
        $role = $this->createRole(uniqid(), $name, $permission);

        $this->saveRole($role);

        unset($role);
    }

    private function createRole(
        string $code,
        string $name,
        string $permission = null
    ): RoleInterface {
        /** @var RoleInterface $role */
        $role = $this->roleFactory->createNew();

        $role->setFallbackLocale('en_US');
        $role->setCurrentLocale('en_US');
        $role->setCode($code);
        $role->setName($name);

        if (null === $permission) {
            return $role;
        }

        $privileges = $this->privilege->getSupportedRouteMap([], true);

        $permission = str_replace(' ', '_', strtolower($permission));

        foreach ($privileges[strtolower($permission)] as $key => $name) {
            $role->addPermission($key);
        }

        return $role;
    }

    private function saveRole(RoleInterface $role): void
    {
        $this->roleRepository->add($role);
    }
}
