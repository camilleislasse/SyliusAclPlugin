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
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Page\Admin\Crud\IndexPageInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\BitBag\SyliusAclPlugin\Behat\Page\Admin\Role\CreatePageInterface;
use Tests\BitBag\SyliusAclPlugin\Behat\Page\Admin\Role\UpdatePageInterface;
use Webmozart\Assert\Assert;

final class RoleContext implements Context
{
    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    /** @var CreatePageInterface */
    private $createPage;

    /** @var IndexPageInterface */
    private $indexPage;

    /** @var UpdatePageInterface */
    private $updatePage;

    /** @var PrivilegeInterface */
    private $privilege;

    /** @var \Faker\Generator */
    private $faker;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        CurrentPageResolverInterface $currentPageResolver,
        NotificationCheckerInterface $notificationChecker,
        CreatePageInterface $createPage,
        IndexPageInterface $indexPage,
        UpdatePageInterface $updatePage,
        PrivilegeInterface $privilege
    ) {
        $this->currentPageResolver = $currentPageResolver;
        $this->notificationChecker = $notificationChecker;
        $this->createPage = $createPage;
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->privilege = $privilege;

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @When I go to the create role page
     */
    public function iGoToTheCreateRolePage(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I fill the code with :code
     */
    public function iFillTheCodeWith(string $code): void
    {
        $this->resolveCurrentPage()->fillField('Code', $code);
    }

    /**
     * @When I fill the name with :name
     * @When I change its name to :name
     */
    public function iFillTheNameWith(string $name): void
    {
        $this->resolveCurrentPage()->fillField('Name', $name);
    }

    /**
     * @When I choose all permissions
     */
    public function iChooseAllPermissions(): void
    {
        $this->resolveCurrentPage()->checkAllPermissions();
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt(): void
    {
        $this->resolveCurrentPage()->create();
    }

    /**
     * @Then I should be notified that the role has been created
     */
    public function iShouldBeNotifiedThatTheRoleHasBeenCreated(): void
    {
        $this->notificationChecker->checkNotification(
            'Role has been successfully created.',
            NotificationType::success()
        );
    }

    /**
     * @When I choose all permissions only for the :resource resource
     */
    public function iChooseAllPermissionsOnlyForTheResource(string $resource): void
    {
        $this->resolveCurrentPage()->checkPermission($resource);
    }

    /**
     * @Then I should be notified that there is already an existing role with provided code
     */
    public function iShouldBeNotifiedThatThereIsAlreadyAnExistingRoleWithProvidedCode(): void
    {
        Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(
            'There is an existing role with this code.',
            false
        ));
    }

    /**
     * @Then I should be notified that :arg1 fields cannot be blank
     */
    public function iShouldBeNotifiedThatFieldsCannotBeBlank(string $fields): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(sprintf(
                '%s cannot be blank.',
                trim($field)
            )));
        }
    }

    /**
     * @When /^I fill "([^"]*)" fields with (\d+) (?:character|characters)$/
     */
    public function iFillFieldsWithCharacters(string $fields, int $length): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            $this->resolveCurrentPage()->fillField(trim($field), $this->faker->text($length));
        }
    }

    /**
     * @Then I should be notified that :fields fields are too long
     */
    public function iShouldBeNotifiedThatFieldsAreTooLong(string $fields): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(sprintf(
                '%s can not be longer than',
                trim($field)
            ), false));
        }
    }

    /**
     * @Then the role :role should have all permissions
     */
    public function theRoleShouldHaveAllPermissions(RoleInterface $role): void
    {
        foreach ($this->privilege->getSupportedRouteMap() as $key => $permission) {
            Assert::true($role->hasPermission($key));
        }
    }

    /**
     * @Then the role :role should have all permissions for :resource resource
     */
    public function theRoleShouldHaveAllPermissionsForProductsResource(RoleInterface $role, string $resource): void
    {
        $permissions = $this->privilege->getSupportedRouteMap([], true);

        $parent = str_replace(' ', '_', strtolower($resource));

        Assert::keyExists($permissions, $parent);

        foreach ($permissions[$parent] as $key => $permission) {
            Assert::true($role->hasPermission($key));
        }
    }

    /**
     * @When I want to browse roles
     * @Given I browse roles
     */
    public function iWantToBrowseRoles(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Then I should see a single role in the list
     * @Then /^there should be (\d+) roles in the list$/
     */
    public function thereShouldBeRolesInTheList(int $count = 1): void
    {
        Assert::same($this->indexPage->countItems(), (int) $count);
    }

    /**
     * @Then I should see the role :roleName in the list
     * @Then this role with name :roleName should appear in the store
     */
    public function iShouldSeeTheRoleInTheList(string $roleName): void
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $roleName]));
    }

    /**
     * @When I delete role with name :roleName
     */
    public function iDeleteRoleWithName(string $roleName): void
    {
        $this->indexPage->deleteResourceOnPage(['name' => $roleName]);
    }

    /**
     * @Then there should not be :arg1 role anymore
     */
    public function thereShouldNotBeRoleAnymore(string $roleName): void
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $roleName]));
    }

    /**
     * @Given I check (also) the :roleName role
     */
    public function iCheckTheRole(string $roleName): void
    {
        $this->indexPage->checkResourceOnPage(['name' => $roleName]);
    }

    /**
     * @Given I delete them
     */
    public function iDeleteThem(): void
    {
        $this->indexPage->bulkDelete();
    }

    /**
     * @When I modify a role :role
     */
    public function iModifyARole(RoleInterface $role): void
    {
        $this->updatePage->open(['id' => $role->getId()]);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges(): void
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then the role :role should be have permission :arg2
     */
    public function theRoleShouldBeHavePermission(RoleInterface $role, string $permission): void
    {
        $this->updatePage->open(['id' => $role->getId()]);

        $this->updatePage->hasPermission($permission);
    }

    /**
     * @return IndexPageInterface|CreatePageInterface|UpdatePageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->createPage,
            $this->indexPage,
            $this->updatePage,
        ]);
    }
}
