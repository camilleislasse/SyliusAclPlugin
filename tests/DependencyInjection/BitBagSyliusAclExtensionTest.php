<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\DependencyInjection;

use BitBag\SyliusAclPlugin\DependencyInjection\BitBagSyliusAclExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

final class SyliusGridExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_sets_configured_permissions_as_parameter(): void
    {
        $this->load([
            'permissions' => [
                'sylius_admin_order_show' => [
                    'label' => 'order.show',
                ],
                'sylius_admin_order_index' => [
                    'parent' => 'orders',
                ],
                'sylius_admin_product_create' => [
                    'enabled' => false,
                ],
                'sylius_admin_product_update' => [
                    'label' => 'product.update',
                    'parent' => 'products',
                    'enabled' => false,
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('bitbag_sylius_acl_plugin.permissions', [
            'sylius_admin_order_show' => [
                'label' => 'order.show',
                'parent' => null,
                'enabled' => true,
            ],
            'sylius_admin_order_index' => [
                'label' => null,
                'parent' => 'orders',
                'enabled' => true,
            ],
            'sylius_admin_product_create' => [
                'label' => null,
                'parent' => null,
                'enabled' => false,
            ],
            'sylius_admin_product_update' => [
                'label' => 'product.update',
                'parent' => 'products',
                'enabled' => false,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions(): array
    {
        return [
            new BitBagSyliusAclExtension(),
        ];
    }
}
