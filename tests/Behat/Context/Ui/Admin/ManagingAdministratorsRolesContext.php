<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use BitBag\SyliusAclPlugin\Entity\RoleInterface;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\BitBag\SyliusAclPlugin\Behat\Page\Admin\Administrator\CreatePageInterface;
use Webmozart\Assert\Assert;

final class ManagingAdministratorsRolesContext implements Context
{
    /** @var CreatePageInterface */
    private $createPage;

    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    /** @var RepositoryInterface */
    private $adminUserRepository;

    public function __construct(
        CreatePageInterface $createPage,
        CurrentPageResolverInterface $currentPageResolver,
        RepositoryInterface $adminUserRepository
    ) {
        $this->createPage = $createPage;
        $this->currentPageResolver = $currentPageResolver;
        $this->adminUserRepository = $adminUserRepository;
    }

    /**
     * @When I chose its :roleName role
     */
    public function iChoseItsRole(string $roleName): void
    {
        $this->resolveCurrentPage()->addRole($roleName);
    }

    /**
     * @Then the administrator :email should have role :role
     */
    public function theAdministratorShouldHaveRole(string $email, RoleInterface $role): void
    {
        /** @var AdminUserInterface|\BitBag\SyliusAclPlugin\Model\AdminUserInterface $adminUser */
        $adminUser = $this->adminUserRepository->findOneBy(['email' => $email]);

        Assert::isInstanceOf($adminUser, AdminUserInterface::class);

        Assert::true($adminUser->hasRoleResource($role));
    }

    /**
     * @return CreatePageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->createPage,
        ]);
    }
}
