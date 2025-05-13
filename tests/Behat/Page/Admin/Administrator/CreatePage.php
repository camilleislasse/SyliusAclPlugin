<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Page\Admin\Administrator;

use DMore\ChromeDriver\ChromeDriver;
use Sylius\Behat\Page\Admin\Administrator\CreatePage as BaseCreatePage;
use Webmozart\Assert\Assert;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function addRole(string $roleName): void
    {
        $role = $this->getElement('roles_choice')->find('css', sprintf('option:contains("%s")', $roleName));
        $this->selectElementFromAttributesDropdown($role->getAttribute('value'));
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'roles_choice' => '#sylius_admin_user_rolesResources',
        ]);
    }

    private function selectElementFromAttributesDropdown(string $id): void
    {
        /** @var ChromeDriver $driver */
        $driver = $this->getDriver();
        Assert::isInstanceOf($driver, ChromeDriver::class);

        $driver->executeScript('$(\'#sylius_admin_user_rolesResources\').dropdown(\'show\');');
        $driver->executeScript(sprintf('$(\'#sylius_admin_user_rolesResources\').dropdown(\'set selected\', \'%s\');', $id));
    }
}
