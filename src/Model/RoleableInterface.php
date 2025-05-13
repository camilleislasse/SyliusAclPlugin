<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Model;

use BitBag\SyliusAclPlugin\Entity\RoleInterface;
use Doctrine\Common\Collections\Collection;

interface RoleableInterface
{
    public function getRolesResources(): Collection;

    public function addRoleResource(RoleInterface $role): void;

    public function removeRoleResource(RoleInterface $role): void;

    public function hasRoleResource(RoleInterface $role): bool;
}
