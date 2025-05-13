<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Twig;

use BitBag\SyliusAclPlugin\Templating\Helper\AclHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AclExtension extends AbstractExtension
{
    public function __construct(
        private AclHelper $aclHelper,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_acl_get_available_grid_actions', [$this->aclHelper, 'getAvailableGridActions']),
            new TwigFunction('bitbag_acl_has_permission', [$this->aclHelper, 'hasPermission']),
        ];
    }
}
