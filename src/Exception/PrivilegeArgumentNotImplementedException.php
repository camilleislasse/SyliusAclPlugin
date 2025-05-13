<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Exception;

final class PrivilegeArgumentNotImplementedException extends \Exception
{
    public function __construct(string $name, string $type)
    {
        $message = sprintf('The privilege "%s" must be configured %s.', $name, $type);

        parent::__construct($message);
    }
}
