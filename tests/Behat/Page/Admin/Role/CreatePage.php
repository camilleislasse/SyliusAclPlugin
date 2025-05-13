<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Page\Admin\Role;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\BitBag\SyliusAclPlugin\Behat\Behaviour\ContainsErrorTrait;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    public function fillField(string $field, string $value): void
    {
        $this->getDocument()->fillField($field, $value);
    }

    public function checkAllPermissions(): void
    {
        $permissions = $this->getDocument()->findAll('css', '.permission .permission__header--container .checkbox');
        /** @var NodeElement $permission */
        foreach ($permissions as $permission) {
            $permission->click();
        }
    }

    public function checkPermission(string $permissionName): void
    {
        $permissions = $this->getDocument()->findAll('css', '.permission');
        /** @var NodeElement $permission */
        foreach ($permissions as $permission) {
            /** @var NodeElement $titleSpan */
            $titleSpan = $permission->find('css', '.title span');
            $title = $titleSpan->getText();
            if ($title !== $permissionName) {
                continue;
            }

            $checkbox = $permission->find('css', '.permission__header--container .checkbox');
            $checkbox->click();
        }
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'permissions_tree' => '#bitbag-permissions-tree',
        ]);
    }
}
