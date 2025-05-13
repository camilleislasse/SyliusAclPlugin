<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException as BaseAccessDeniedHttpException;

final class AccessDeniedHttpException extends BaseAccessDeniedHttpException
{
    public function __construct(
        string $permission,
        \Exception $previous = null,
        int $code = 0,
    ) {
        $message = sprintf('Access denied for the permission "%s".', $permission);

        parent::__construct($message, $previous, $code);
    }
}
