<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Entity;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface RoleInterface extends ResourceInterface, TranslatableInterface, CodeAwareInterface
{
    public function getPermissions(): array;

    public function setPermissions(array $permissions): void;

    public function addPermission(string $permission): void;

    public function removePermission(string $permission): void;

    public function hasPermission(string $permission): bool;

    public function getName(): ?string;

    public function setName(?string $name): void;
}
