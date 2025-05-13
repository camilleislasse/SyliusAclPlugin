<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Entity;

use BitBag\SyliusAclPlugin\Model\RoleableInterface;
use BitBag\SyliusAclPlugin\Model\ToggleablePermissionCheckerInterface;
use Sylius\Component\Core\Model\AdminUserInterface as BaseAdminUserInterface;

interface AdminUserInterface extends
    BaseAdminUserInterface,
    RoleableInterface,
    ToggleablePermissionCheckerInterface
{
}
