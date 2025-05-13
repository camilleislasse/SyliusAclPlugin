<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Twig;

use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use BitBag\SyliusAclPlugin\Twig\RoutingExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;

final class RoutingExtensionSpec extends ObjectBehavior
{
    public function let(
        UrlGeneratorInterface $generator,
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $this->beConstructedWith($generator, $adminPermissionResolver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RoutingExtension::class);
    }

    public function it_extends_abstract_extension(): void
    {
        $this->shouldHaveType(AbstractExtension::class);
    }

    public function it_returns_functions(): void
    {
        $functions = $this->getFunctions();
        $functions->shouldHaveCount(2);

        foreach ($functions as $function) {
            $function->shouldHaveType(\Twig_SimpleFunction::class);
        }
    }

    public function it_returns_path_if_has_permission(
        UrlGeneratorInterface $generator,
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $adminPermissionResolver->resolvePermission('product_create')->willReturn(true);
        $generator->generate('product_create', [], UrlGeneratorInterface::ABSOLUTE_PATH)->willReturn('/product/create');

        $this->getPath('product_create')->shouldReturn('/product/create');
    }

    public function it_returns_access_denied_if_not_has_permission(
        UrlGeneratorInterface $generator,
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $adminPermissionResolver->resolvePermission('product_create')->willReturn(false);
        $generator->generate('product_create', [], UrlGeneratorInterface::ABSOLUTE_URL)->shouldNotBeCalled();

        $this->getPath('product_create')->shouldReturn(RoutingExtension::ACCESS_DENIED);
    }

    public function it_returns_url_if_has_permission(
        UrlGeneratorInterface $generator,
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $adminPermissionResolver->resolvePermission('product_create')->willReturn(true);
        $generator->generate('product_create', [], UrlGeneratorInterface::ABSOLUTE_URL)->willReturn('/product/create');

        $this->getUrl('product_create')->shouldReturn('/product/create');
    }

    public function it_returns_access_denied_if_not_has_permission_to_url(
        UrlGeneratorInterface $generator,
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $adminPermissionResolver->resolvePermission('product_create')->willReturn(false);
        $generator->generate('product_create', [], UrlGeneratorInterface::ABSOLUTE_URL)->shouldNotBeCalled();

        $this->getPath('product_create')->shouldReturn(RoutingExtension::ACCESS_DENIED);
    }
}
