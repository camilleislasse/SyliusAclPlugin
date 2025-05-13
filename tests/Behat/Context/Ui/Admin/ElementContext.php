<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\BitBag\SyliusAclPlugin\Behat\Service\ElementCheckerInterface;
use Webmozart\Assert\Assert;

final class ElementContext implements Context
{
    /** @var ElementCheckerInterface */
    private $elementChecker;

    public function __construct(ElementCheckerInterface $elementChecker)
    {
        $this->elementChecker = $elementChecker;
    }

    /**
     * @Then /^I should see the button(?:|s) "([^"]+)"$/
     */
    public function iShouldSeeTheButton(string $buttons): void
    {
        $buttons = explode(',', $buttons);

        foreach ($buttons as $button) {
            Assert::true($this->elementChecker->isVisibleButton(trim($button)));
        }
    }

    /**
     * @Then /^I should not see the button(?:|s) "([^"]+)"$/
     */
    public function iShouldNotSeeTheButtons(string $buttons): void
    {
        $buttons = explode(',', $buttons);

        foreach ($buttons as $button) {
            Assert::false($this->elementChecker->isVisibleButton(trim($button)));
        }
    }

    /**
     * @Then I should not see taxon tree
     */
    public function iShouldNotSeeTaxonTree(): void
    {
        Assert::true(
            $this->elementChecker->isElement('.admin-layout__content .three.wide.column .sylius-tree') === false &&
            $this->elementChecker->isElement('#content .three.wide.column .sylius-tree') === false
        );
    }

    /**
     * @Then I should see taxon tree
     */
    public function iShouldSeeTaxonTree(): void
    {
        Assert::true(
            $this->elementChecker->isElement('.admin-layout__content .three.wide.column .sylius-tree') ||
            $this->elementChecker->isElement('#content .three.wide.column .sylius-tree')
        );
    }

    /**
     * @Given /^I should see (\d+) sidebar element$/
     * @Given /^I should see (\d+) sidebar elements$/
     * @Given I should see single sidebar element
     */
    public function iShouldSeeSidebarElement(int $number = 1): void
    {
        Assert::true(
            $this->elementChecker->countElements('.admin-layout__sidebar div.item a.item') === $number ||
            $this->elementChecker->countElements('#sidebar div.item a.item') === $number
        );
    }

    /**
     * @Given I should see a reference to :name in the sidebar
     */
    public function iShouldSeeAReferenceToProductsInTheSidebar(string $name): void
    {
        Assert::true(
            $this->elementChecker->isElementWithName('.admin-layout__sidebar div.item a.item', $name) ||
            $this->elementChecker->isElementWithName('#sidebar div.item a.item', $name)
        );
    }

    /**
     * @Given I should not see a reference to :name in the sidebar
     */
    public function iShouldNotSeeAReferenceToProductsInTheSidebar(string $name): void
    {
        Assert::false(
            $this->elementChecker->isElementWithName('.admin-layout__sidebar div.item a.item', $name) ||
            $this->elementChecker->isElementWithName('#sidebar div.item a.item', $name)
        );
    }
}
