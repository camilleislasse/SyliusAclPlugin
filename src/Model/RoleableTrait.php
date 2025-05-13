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

trait RoleableTrait
{
    /** @var Collection|RoleInterface[] */
    protected $rolesResources;

    public function getRolesResources(): Collection
    {
        return $this->rolesResources;
    }

    public function addRoleResource(RoleInterface $role): void
    {
        if (!$this->hasRoleResource($role)) {
            $this->rolesResources->add($role);
        }
    }

    public function removeRoleResource(RoleInterface $role): void
    {
        if ($this->hasRoleResource($role)) {
            $this->rolesResources->removeElement($role);
        }
    }

    public function hasRoleResource(RoleInterface $role): bool
    {
        return $this->rolesResources->contains($role);
    }
}
