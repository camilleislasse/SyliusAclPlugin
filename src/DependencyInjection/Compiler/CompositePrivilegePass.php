<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

final class CompositePrivilegePass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'bitbag_sylius_acl_plugin.privilege.resource',
            'bitbag_sylius_acl_plugin.context.privilege.composite',
            'bitbag_sylius_acl_plugin.privilege',
            'addPrivilege',
        );
    }
}
