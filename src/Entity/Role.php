<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Entity;

use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class Role implements RoleInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $code;

    /** @var array */
    protected $permissions = [];

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function addPermission(string $permission): void
    {
        if (!in_array($permission, $this->permissions, true)) {
            $this->permissions[] = $permission;
        }
    }

    public function removePermission(string $permission): void
    {
        if (false !== $key = array_search($permission, $this->permissions, true)) {
            unset($this->permissions[$key]);
            $this->permissions = array_values($this->permissions);
        }
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions(), true);
    }

    public function getName(): ?string
    {
        $roleTranslation = $this->getRoleTranslation();

        if (!$roleTranslation instanceof RoleTranslationInterface) {
            throw new \UnexpectedValueException('Expected instance of RoleTranslationInterface');
        }

        return $roleTranslation->getName();
    }

    public function setName(?string $name): void
    {
        $roleTranslation = $this->getRoleTranslation();

        if (!$roleTranslation instanceof RoleTranslationInterface) {
            throw new \UnexpectedValueException('Expected instance of RoleTranslationInterface');
        }

        $roleTranslation->setName($name);
    }

    protected function getRoleTranslation(): TranslationInterface
    {
        return $this->getTranslation();
    }

    protected function createTranslation(): RoleTranslationInterface
    {
        return new RoleTranslation();
    }
}
