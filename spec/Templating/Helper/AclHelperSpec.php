<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Templating\Helper;

use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use BitBag\SyliusAclPlugin\Templating\Helper\AclHelper;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Symfony\Component\Templating\Helper\Helper;
use Webmozart\Assert\Assert;

class AclHelperSpec extends ObjectBehavior
{
    public function let(AdminPermissionResolverInterface $adminPermissionResolver): void
    {
        $this->beConstructedWith($adminPermissionResolver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AclHelper::class);
    }

    public function it_extends_helper(): void
    {
        $this->shouldHaveType(Helper::class);
    }

    public function it_gets_available_grids_actions(
        AdminPermissionResolverInterface $adminPermissionResolver,
        ResourceGridView $resourceGridView,
        RequestConfiguration $requestConfiguration,
        Action $secondAction,
        Action $fourthAction
    ): void {
        $resourceGridView->getRequestConfiguration()->willReturn($requestConfiguration);
        $firstAction = Action::fromNameAndType('create_product', 'create');
        $firstAction->setOptions([
            'link' => [
                'route' => 'create_product',
            ],
        ]);
        $adminPermissionResolver->resolvePermission('create_product')->willReturn(true);
        $secondAction->getType()->willReturn('update');
        $secondAction->getOptions()->willReturn([
            'link' => [
                'route' => 'update_product',
            ],
        ]);
        $adminPermissionResolver->resolvePermission('update_product')->willReturn(false);
        $thirdAction = Action::fromNameAndType('data_transfer', 'links');
        $thirdAction->setOptions([
            'links' => [
                'import' => [
                    'route' => 'import_product',
                ],
                'export' => [
                    'route' => 'export_product',
                ],
            ],
        ]);
        $adminPermissionResolver->resolvePermission('import_product')->willReturn(true);
        $adminPermissionResolver->resolvePermission('export_product')->willReturn(false);
        $fourthAction->getType()->willReturn('links');
        $fourthAction->getOptions()->willReturn([
            'links' => [
                'generation_variants' => [
                    'route' => 'generation_variants_product',
                ],
            ],
        ]);
        $fourthAction->setOptions(['links' => []])->shouldBeCalled();
        $adminPermissionResolver->resolvePermission('generation_variants_product')->willReturn(false);

        $result = $this->getAvailableGridActions($resourceGridView, [
            $firstAction, $secondAction, $thirdAction, $fourthAction,
        ])->getWrappedObject();

        Assert::same($thirdAction->getName(), $result[2]->getName());
        Assert::true(isset($result[2]->getOptions()['links']['import']));
        Assert::count($result[2]->getOptions()['links'], 1);
        Assert::same($firstAction, $result[0]);
    }

    public function it_gets_available_grids_bulk_actions(
        AdminPermissionResolverInterface $adminPermissionResolver,
        ResourceGridView $resourceGridView,
        RequestConfiguration $requestConfiguration
    ): void {
        $resourceGridView->getRequestConfiguration()->willReturn($requestConfiguration);
        $firstAction = Action::fromNameAndType('create_product', 'create');
        $firstAction->setOptions([]);
        $adminPermissionResolver->resolvePermission('product_bulk_create')->willReturn(true);
        $requestConfiguration->getRouteName('bulk_create')->willReturn('product_bulk_create');

        $secondAction = Action::fromNameAndType('update_product', 'update');
        $secondAction->setOptions([]);
        $adminPermissionResolver->resolvePermission('product_bulk_update')->willReturn(false);
        $requestConfiguration->getRouteName('bulk_update')->willReturn('product_bulk_update');

        $this->getAvailableGridActions($resourceGridView, [$firstAction], true)->shouldReturn([$firstAction]);
    }

    public function it_gets_name(): void
    {
        $this->getName()->shouldReturn('bitbag_acl');
    }

    public function it_returns_true_if_has_permission(
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $adminPermissionResolver->resolvePermission('create')->willReturn(true);

        $this->hasPermission('create')->shouldReturn(true);
    }

    public function it_returns_false_if_not_has_permission(
        AdminPermissionResolverInterface $adminPermissionResolver
    ): void {
        $adminPermissionResolver->resolvePermission('create')->willReturn(false);

        $this->hasPermission('create')->shouldReturn(false);
    }
}
