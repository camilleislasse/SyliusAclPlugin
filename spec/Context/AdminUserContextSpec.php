<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Context;

use BitBag\SyliusAclPlugin\Context\AdminUserContext;
use BitBag\SyliusAclPlugin\Context\AdminUserContextInterface;
use BitBag\SyliusAclPlugin\Model\AdminUserInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AdminUserContextSpec extends ObjectBehavior
{
    public function let(TokenStorageInterface $tokenStorage): void
    {
        $this->beConstructedWith($tokenStorage);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AdminUserContext::class);
    }

    public function it_implements_admin_user_context_interface(): void
    {
        $this->shouldHaveType(AdminUserContextInterface::class);
    }

    public function it_returns_admin_user(
        TokenStorageInterface $tokenStorage,
        AdminUserInterface $adminUser,
        TokenInterface $token
    ): void {
        $tokenStorage->getToken()->willReturn($token);
        $token->getUser()->willReturn($adminUser);

        $this->getAdminUser()->shouldReturn($adminUser);
    }

    public function it_returns_null_if_token_is_null(
        TokenStorageInterface $tokenStorage
    ): void {
        $tokenStorage->getToken()->willReturn(null);

        $this->getAdminUser()->shouldReturn(null);
    }

    public function it_returns_null_if_user_not_instance_of_admin_user_interface(
        TokenStorageInterface $tokenStorage,
        UserInterface $customer,
        TokenInterface $token
    ): void {
        $tokenStorage->getToken()->willReturn($token);
        $token->getUser()->willReturn($customer);

        $this->getAdminUser()->shouldReturn(null);
    }
}
