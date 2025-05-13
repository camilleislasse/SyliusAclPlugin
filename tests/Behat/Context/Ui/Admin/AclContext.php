<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Sylius\Behat\Context\Ui\Admin\ManagingOrdersContext;
use Sylius\Behat\Context\Ui\Admin\ManagingProductsContext;
use Sylius\Behat\Page\Admin\Product\UpdateConfigurableProductPageInterface;
use Sylius\Behat\Page\Admin\Product\UpdateSimpleProductPageInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class AclContext implements Context
{
    /** @var Session */
    private $session;

    /** @var ManagingProductsContext */
    private $managingProductsContext;

    /** @var ManagingOrdersContext */
    private $managingOrdersContext;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var UpdateSimpleProductPageInterface */
    private $updateSimpleProductPage;

    /** @var UpdateConfigurableProductPageInterface */
    private $updateConfigurableProductPage;

    public function __construct(
        Session $session,
        ManagingProductsContext $managingProductsContext,
        ManagingOrdersContext $managingOrdersContext,
        SharedStorageInterface $sharedStorage,
        UpdateSimpleProductPageInterface $updateSimpleProductPage,
        UpdateConfigurableProductPageInterface $updateConfigurableProductPage
    ) {
        $this->session = $session;
        $this->managingProductsContext = $managingProductsContext;
        $this->managingOrdersContext = $managingOrdersContext;
        $this->sharedStorage = $sharedStorage;
        $this->updateSimpleProductPage = $updateSimpleProductPage;
        $this->updateConfigurableProductPage = $updateConfigurableProductPage;
    }

    /**
     * @Given I want to trying modify the :product product
     */
    public function iWantToModifyAProduct(ProductInterface $product)
    {
        $this->sharedStorage->set('product', $product);

        if ($product->isSimple()) {
            try {
                $this->updateSimpleProductPage->open(['id' => $product->getId()]);
            } catch (UnexpectedPageException $unexpectedPageException) {
                return;
            }
        }

        try {
            $this->updateConfigurableProductPage->open(['id' => $product->getId()]);
        } catch (UnexpectedPageException $unexpectedPageException) {
            return;
        }
    }

    /**
     * @When I'm viewing the summary of the order :order
     */
    public function iViewToTryingTheSummaryOfTheOrder(OrderInterface $order): void
    {
        try {
            if (method_exists($this->managingOrdersContext, 'iSeeTheOrder')) {
                $this->managingOrdersContext->iSeeTheOrder($order);
            }
            if (method_exists($this->managingOrdersContext, 'iViewTheSummaryOfTheOrder')) {
                $this->managingOrdersContext->iViewTheSummaryOfTheOrder($order);
            }
        } catch (UnexpectedPageException $unexpectedPageException) {
            return;
        }
    }

    /**
     * @Then /^I should get a (\d+) HTTP response$/
     */
    public function iShouldBeInformedAboutTheDeniedAccess(int $code): void
    {
        Assert::same($this->session->getStatusCode(), $code);
    }
}
