<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Menu;

use Knp\Menu\MenuItem;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class MainMenuBuilder extends AbstractPermissionChecker
{
    public function buildMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        /** @var MenuItem $menu */
        $menu = $menuBuilderEvent->getMenu();

        $this->removeMenuItemWithoutPermission($menu);
        $this->menuCleanup($menu);
    }
}
