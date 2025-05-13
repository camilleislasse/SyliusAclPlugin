<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\DependencyInjection;

use BitBag\SyliusAclPlugin\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function it_returns_set_own_config(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['permissions' => [
                    'sylius_admin_product_create' => [
                        'label' => 'create',
                        'parent' => 'products',
                        'enabled' => false,
                    ],
                ]],
            ],
            ['permissions' => [
                'sylius_admin_product_create' => [
                    'label' => 'create',
                    'parent' => 'products',
                    'enabled' => false,
                ],
            ]],
            'permissions'
        );
    }

    /**
     * @test
     */
    public function it_enabled_on_by_default(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['permissions' => [
                    'sylius_admin_product_create' => [],
                ]],
            ],
            ['permissions' => [
                'sylius_admin_product_create' => [
                    'enabled' => true,
                ],
            ]],
            'permissions.*.enabled'
        );
    }

    /**
     * @test
     */
    public function it_label_has_null_by_default(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['permissions' => [
                    'sylius_admin_product_create' => [],
                ]],
            ],
            ['permissions' => [
                'sylius_admin_product_create' => [
                    'label' => null,
                ],
            ]],
            'permissions.*.label'
        );
    }

    /**
     * @test
     */
    public function it_parent_has_null_by_default(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['permissions' => [
                    'sylius_admin_product_create' => [],
                ]],
            ],
            ['permissions' => [
                'sylius_admin_product_create' => [
                    'parent' => null,
                ],
            ]],
            'permissions.*.parent'
        );
    }

    /**
     * @test
     */
    public function its_base_fields_can_be_overwritten(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['permissions' => [
                    'sylius_admin_product_create' => [
                        'label' => 'action.create',
                    ],
                ]],
                ['permissions' => [
                    'sylius_admin_product_create' => [
                        'label' => 'action.product_create',
                    ],
                ]],
            ],
            ['permissions' => [
                'sylius_admin_product_create' => [
                    'label' => 'action.product_create',
                ],
            ]],
            'permissions.*.label'
        );

        $this->assertProcessedConfigurationEquals(
            [
                ['permissions' => [
                    'sylius_admin_product_create' => [
                        'parent' => 'actions',
                    ],
                ]],
                ['permissions' => [
                    'sylius_admin_product_create' => [
                        'parent' => 'products',
                    ],
                ]],
            ],
            ['permissions' => [
                'sylius_admin_product_create' => [
                    'parent' => 'products',
                ],
            ]],
            'permissions.*.parent'
        );

        $this->assertProcessedConfigurationEquals(
            [
                ['permissions' => [
                    'sylius_admin_product_create' => [
                        'enabled' => true,
                    ],
                ]],
                ['permissions' => [
                    'sylius_admin_product_create' => [
                        'enabled' => false,
                    ],
                ]],
            ],
            ['permissions' => [
                'sylius_admin_product_create' => [
                    'enabled' => false,
                ],
            ]],
            'permissions.*.enabled'
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
