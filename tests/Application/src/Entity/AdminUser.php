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

use BitBag\SyliusAclPlugin\Model\RoleableTrait;
use BitBag\SyliusAclPlugin\Model\ToggleablePermissionCheckerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

class AdminUser extends BaseAdminUser implements AdminUserInterface
{
    use ToggleablePermissionCheckerTrait;

    use RoleableTrait;

    public function __construct()
    {
        parent::__construct();

        $this->rolesResources = new ArrayCollection();
    }
}
