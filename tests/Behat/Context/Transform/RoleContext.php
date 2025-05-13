<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use BitBag\SyliusAclPlugin\Entity\RoleInterface;
use BitBag\SyliusAclPlugin\Repository\RoleRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class RoleContext implements Context
{
    /** @var RepositoryInterface */
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @Transform :role
     * @Transform /^"([^"]+)" role/
     */
    public function getPaymentMethodByName(string $roleName): RoleInterface
    {
        $roles = $this->roleRepository->findByName($roleName, 'en_US');

        Assert::eq(
            count($roles),
            1,
            sprintf('%d roles has been found with name "%s".', count($roles), $roleName)
        );

        return $roles[0];
    }
}
