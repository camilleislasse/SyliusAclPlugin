<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Checker;

use BitBag\SyliusAclPlugin\Checker\AuthorizationCheckerInterface;
use BitBag\SyliusAclPlugin\Checker\CompositeAuthorizationChecker;
use PhpSpec\ObjectBehavior;

final class CompositeAuthorizationCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CompositeAuthorizationChecker::class);
    }

    public function it_implements_authorization_checker_interface(): void
    {
        $this->shouldHaveType(AuthorizationCheckerInterface::class);
    }

    public function it_returns_successful_authorizations(
        AuthorizationCheckerInterface $authorizationChecker
    ): void {
        $authorizationChecker->isGranted('create')->willReturn(true);

        $this->addAuthorizationChecker($authorizationChecker);

        $this->isGranted('create')->shouldReturn(true);
    }

    public function it_returns_failed_authorizations(
        AuthorizationCheckerInterface $authorizationChecker
    ): void {
        $authorizationChecker->isGranted('create')->willReturn(false);

        $this->addAuthorizationChecker($authorizationChecker);

        $this->isGranted('create')->shouldReturn(false);
    }
}
