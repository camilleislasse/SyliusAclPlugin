<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Entity;

use BitBag\SyliusAclPlugin\Entity\RoleTranslation;
use BitBag\SyliusAclPlugin\Entity\RoleTranslationInterface;
use PhpSpec\ObjectBehavior;

final class RoleTranslationSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RoleTranslation::class);
    }

    public function it_implements_role_translation_interface(): void
    {
        $this->shouldHaveType(RoleTranslationInterface::class);
    }

    public function it_has_null_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    public function it_gets_name(): void
    {
        $this->setName('Accountant');

        $this->getName()->shouldReturn('Accountant');
    }
}
