<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Checker;

use Laminas\Stdlib\PriorityQueue;

final class CompositeAuthorizationChecker implements AuthorizationCheckerInterface
{
    private PriorityQueue $authorizationsChecker;

    public function __construct()
    {
        $this->authorizationsChecker = new PriorityQueue();
    }

    public function addAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker, int $priority = 0): void
    {
        $this->authorizationsChecker->insert($authorizationChecker, $priority);
    }

    public function isGranted(string $permission = null): bool
    {
        foreach ($this->authorizationsChecker as $authorizationChecker) {
            if (!$authorizationChecker->isGranted($permission)) {
                return false;
            }
        }

        return true;
    }
}
