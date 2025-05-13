<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Twig;

use BitBag\SyliusAclPlugin\Templating\Helper\AclHelper;
use BitBag\SyliusAclPlugin\Twig\AclExtension;
use PhpSpec\ObjectBehavior;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AclExtensionSpec extends ObjectBehavior
{
    public function let(AclHelper $aclHelper): void
    {
        $this->beConstructedWith($aclHelper);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AclExtension::class);
    }

    public function it_extends_twig_extension(): void
    {
        $this->shouldHaveType(AbstractExtension::class);
    }

    public function it_returns_functions(): void
    {
        $functions = $this->getFunctions();
        $functions->shouldHaveCount(2);

        foreach ($functions as $function) {
            $function->shouldHaveType(TwigFunction::class);
        }
    }
}
