<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Exception;

use BitBag\SyliusAclPlugin\Exception\PrivilegeArgumentNotImplementedException;
use PhpSpec\ObjectBehavior;

final class PrivilegeArgumentNotImplementedExceptionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('create', 'label');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PrivilegeArgumentNotImplementedException::class);
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(\Exception::class);
    }

    public function it_has_custom_message(): void
    {
        $this->getMessage()->shouldReturn('The privilege "create" must be configured label.');
    }
}
