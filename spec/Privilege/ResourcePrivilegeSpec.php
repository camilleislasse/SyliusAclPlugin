<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Privilege;

use BitBag\SyliusAclPlugin\Exception\PrivilegeArgumentNotImplementedException;
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use BitBag\SyliusAclPlugin\Privilege\ResourcePrivilege;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class ResourcePrivilegeSpec extends ObjectBehavior
{
    public function let(RouterInterface $router): void
    {
        $this->beConstructedWith($router, []);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourcePrivilege::class);
    }

    public function it_implements_privilege_interface(): void
    {
        $this->shouldHaveType(PrivilegeInterface::class);
    }

    public function it_gets_supported_route_map(
        RouterInterface $router
    ): void {
        $routeCollection = new RouteCollection();

        $productCreate = new Route('/product/create', [
            '_controller' => 'sylius.controller.product:createAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
        ]);

        $orders = new Route('/order', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
        ]);

        $cancelOrder = new Route('/order/cancel', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'state_machine' => [
                    'graph' => 'sylius_order',
                    'transition' => 'cancel',
                ],
            ],
        ]);

        $productPartial = new Route('/partial/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $productAjax = new Route('/ajax/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $productUpdate = new Route('/product/update', [
            '_controller' => 'sylius.controller.product:updateAction',
        ]);

        $productShow = new Route('/product', [
            '_controller' => 'sylius.controller.product:showAction',
            '_sylius' => [
                'permission' => false,
            ],
        ]);

        $productIndex = new Route('/products', [
            '_controller' => 'sylius.controller.product:showAction',
            '_sylius' => [
                'permission' => false,
            ],
        ]);

        $routeCollection->add('admin_product_create', $productCreate);
        $routeCollection->add('admin_order_index', $orders);
        $routeCollection->add('admin_order_cancel', $cancelOrder);
        $routeCollection->add('admin_partial_product_latest', $productPartial);
        $routeCollection->add('admin_ajax_product', $productAjax);
        $routeCollection->add('admin_product_update', $productUpdate);
        $routeCollection->add('admin_product_show', $productShow);
        $routeCollection->add('admin_product_index', $productIndex);

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->getSupportedRouteMap()->shouldReturn([
            'admin_ajax_product' => 'ajax.product',
            'admin_order_cancel' => 'action.order_cancel',
            'admin_order_index' => 'action.index',
            'admin_partial_product_latest' => 'partial.product_latest',
            'admin_product_create' => 'action.create',
        ]);
    }

    public function it_gets_supported_route_tree(
        RouterInterface $router
    ): void {
        $routeCollection = new RouteCollection();

        $productCreate = new Route('/product/create', [
            '_controller' => 'sylius.controller.product:createAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
        ]);

        $orders = new Route('/order', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
        ]);

        $cancelOrder = new Route('/order/cancel', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'state_machine' => [
                    'graph' => 'sylius_order',
                    'transition' => 'cancel',
                ],
            ],
        ]);

        $productPartial = new Route('/partial/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $productAjax = new Route('/ajax/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $routeCollection->add('admin_product_create', $productCreate);
        $routeCollection->add('admin_order_index', $orders);
        $routeCollection->add('admin_order_cancel', $cancelOrder);
        $routeCollection->add('admin_partial_product_latest', $productPartial);
        $routeCollection->add('admin_ajax_product', $productAjax);

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->getSupportedRouteMap([], true)->shouldReturn([
            'orders' => [
                'admin_order_index' => 'action.index',
                'admin_order_cancel' => 'action.order_cancel',
            ],
            'products' => [
                'admin_product_create' => 'action.create',
                'admin_partial_product_latest' => 'partial.product_latest',
                'admin_ajax_product' => 'ajax.product',
            ],
        ]);
    }

    public function it_overrides_supported_route_map(
        RouterInterface $router
    ): void {
        $permissions = [
            'admin_order_cancel' => [
                'label' => 'state_machine.orders_cancel',
            ],
            'admin_partial_product_latest' => [
                'enabled' => false,
            ],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $productCreate = new Route('/product/create', [
            '_controller' => 'sylius.controller.product:createAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
            '_bitbag_sylius_acl_plugin' => [
                'label' => 'products_create',
            ],
        ]);

        $orders = new Route('/order', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
            '_bitbag_sylius_acl_plugin' => [
                'enabled' => false,
            ],
        ]);

        $cancelOrder = new Route('/order/cancel', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'state_machine' => [
                    'graph' => 'sylius_order',
                    'transition' => 'cancel',
                ],
            ],
            '_bitbag_sylius_acl_plugin' => [
                'label' => 'cancel',
            ],
        ]);

        $productPartial = new Route('/partial/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $productAjax = new Route('/ajax/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $routeCollection->add('admin_product_create', $productCreate);
        $routeCollection->add('admin_order_index', $orders);
        $routeCollection->add('admin_order_cancel', $cancelOrder);
        $routeCollection->add('admin_partial_product_latest', $productPartial);
        $routeCollection->add('admin_ajax_product', $productAjax);

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->getSupportedRouteMap()->shouldReturn([
            'admin_ajax_product' => 'ajax.product',
            'admin_order_cancel' => 'state_machine.orders_cancel',
            'admin_product_create' => 'products_create',
        ]);
    }

    public function it_overrides_supported_route_tree(
        RouterInterface $router
    ): void {
        $permissions = [
            'admin_order_cancel' => [
                'label' => 'state_machine.orders_cancel',
                'parent' => 'shop_orders',
            ],
            'admin_product_create' => [
                'label' => 'product_create',
            ],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $productCreate = new Route('/product/create', [
            '_controller' => 'sylius.controller.product:createAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
            '_bitbag_sylius_acl_plugin' => [
                'parent' => 'shop_products',
            ],
        ]);

        $orders = new Route('/order', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
            '_bitbag_sylius_acl_plugin' => [
                'parent' => 'shop_orders',
            ],
        ]);

        $cancelOrder = new Route('/order/cancel', [
            '_controller' => 'sylius.controller.order:indexAction',
            '_sylius' => [
                'permission' => true,
                'state_machine' => [
                    'graph' => 'sylius_order',
                    'transition' => 'cancel',
                ],
            ],
            '_bitbag_sylius_acl_plugin' => [
                'label' => 'cancel',
                'parent' => 'customer_orders',
            ],
        ]);

        $productPartial = new Route('/partial/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $productAjax = new Route('/ajax/product', [
            '_controller' => 'sylius.controller.product:indexAction',
            '_sylius' => [
                'permission' => true,
            ],
        ]);

        $routeCollection->add('admin_product_create', $productCreate);
        $routeCollection->add('admin_order_index', $orders);
        $routeCollection->add('admin_order_cancel', $cancelOrder);
        $routeCollection->add('admin_partial_product_latest', $productPartial);
        $routeCollection->add('admin_ajax_product', $productAjax);

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->getSupportedRouteMap([], true)->shouldReturn([
            'products' => [
                'admin_partial_product_latest' => 'partial.product_latest',
                'admin_ajax_product' => 'ajax.product',
            ],
            'shop_orders' => [
                'admin_order_index' => 'action.index',
                'admin_order_cancel' => 'state_machine.orders_cancel',
            ],
            'shop_products' => [
                'admin_product_create' => 'product_create',
            ],
        ]);
    }

    public function it_adds_supported_route_map(
        RouterInterface $router
    ): void {
        $permissions = [
            'data_export' => [
                'label' => 'data_export',
                'enabled' => true,
            ],
            'data_export_xml' => [
                'label' => 'data_export',
                'enabled' => false,
            ],
            'data_export_cvs' => [
                'label' => 'data_export_cvs_products',
                'enabled' => true,
            ],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $productCreate = new Route('/product/create', [
            '_controller' => 'sylius.controller.product:createAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
            '_bitbag_sylius_acl_plugin' => [
                'parent' => 'shop_products',
            ],
        ]);

        $import = new Route('/import', [
            '_controller' => 'sylius.controller.import',
            '_bitbag_sylius_acl_plugin' => [
                'label' => 'data_import',
            ],
        ]);

        $export = new Route('/import', [
            '_controller' => 'sylius.controller.import',
            '_bitbag_sylius_acl_plugin' => [
                'label' => 'data_export_cvs',
            ],
        ]);

        $routeCollection->add('admin_product_create', $productCreate);
        $routeCollection->add('data_import', $import);
        $routeCollection->add('data_export_cvs', $export);

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->getSupportedRouteMap()->shouldReturn([
            'admin_product_create' => 'action.create',
            'data_export' => 'data_export',
            'data_export_cvs' => 'data_export_cvs_products',
            'data_import' => 'data_import',
        ]);
    }

    public function it_adds_supported_route_tree(
        RouterInterface $router
    ): void {
        $permissions = [
            'data_export' => [
                'label' => 'data_export',
                'enabled' => true,
                'parent' => 'data_transfer',
            ],
            'data_export_xml' => [
                'label' => 'data_export',
                'enabled' => false,
            ],
            'data_export_cvs' => [
                'label' => 'data_export_cvs_products',
                'enabled' => true,
                'parent' => 'products',
            ],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $productCreate = new Route('/product/create', [
            '_controller' => 'sylius.controller.product:createAction',
            '_sylius' => [
                'permission' => true,
                'section' => 'admin',
            ],
        ]);

        $import = new Route('/import', [
            '_controller' => 'sylius.controller.import',
            '_bitbag_sylius_acl_plugin' => [
                'label' => 'data_import',
                'parent' => 'data_transfer',
            ],
        ]);

        $export = new Route('/import', [
            '_controller' => 'sylius.controller.import',
            '_bitbag_sylius_acl_plugin' => [
                'label' => 'data_export_cvs',
                'parent' => 'data_transfer',
            ],
        ]);

        $routeCollection->add('admin_product_create', $productCreate);
        $routeCollection->add('data_import', $import);
        $routeCollection->add('data_export_cvs', $export);

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->getSupportedRouteMap([], true)->shouldReturn([
            'data_transfer' => [
                'data_import' => 'data_import',
                'data_export' => 'data_export',
            ],
            'products' => [
                'admin_product_create' => 'action.create',
                'data_export_cvs' => 'data_export_cvs_products',
            ],
        ]);
    }

    public function it_throw_argument_not_implemented_if_not_has_label(
        RouterInterface $router
    ): void {
        $permissions = [
            'data_export' => [],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->shouldThrow(
            new PrivilegeArgumentNotImplementedException('data_export', 'label')
        )->during('getSupportedRouteMap');
    }

    public function it_throw_argument_not_implemented_if_label_is_null(
        RouterInterface $router
    ): void {
        $permissions = [
            'data_export' => [
                'label' => null,
            ],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->shouldThrow(
            new PrivilegeArgumentNotImplementedException('data_export', 'label')
        )->during('getSupportedRouteMap');
    }

    public function it_throw_argument_not_implemented_if_not_has_parent(
        RouterInterface $router
    ): void {
        $permissions = [
            'data_export' => [
                'label' => 'export',
            ],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->shouldThrow(
            new PrivilegeArgumentNotImplementedException('data_export', 'parent')
        )->during('getSupportedRouteMap', [[], true]);
    }

    public function it_throw_argument_not_implemented_if_parent_is_null(
        RouterInterface $router
    ): void {
        $permissions = [
            'data_export' => [
                'label' => 'export',
                'parent' => null,
            ],
        ];

        $this->beConstructedWith($router, $permissions);

        $routeCollection = new RouteCollection();

        $router->getRouteCollection()->willReturn($routeCollection);

        $this->shouldThrow(
            new PrivilegeArgumentNotImplementedException('data_export', 'parent')
        )->during('getSupportedRouteMap', [[], true]);
    }
}
