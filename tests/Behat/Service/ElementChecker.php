<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Service;

use Behat\Mink\Element\NodeElement;
use Tests\BitBag\SyliusAclPlugin\Behat\Service\Accessor\ElementAccessorInterface;

final class ElementChecker implements ElementCheckerInterface
{
    /** @var ElementAccessorInterface */
    private $elementAccessor;

    public function __construct(ElementAccessorInterface $elementAccessor)
    {
        $this->elementAccessor = $elementAccessor;
    }

    public function isVisibleButton(string $name): bool
    {
        $elements = $this->elementAccessor->getButtonElements($name);

        if (empty($elements)) {
            return false;
        }

        /** @var NodeElement $element */
        foreach ($elements as $element) {
            if ($element->isVisible()) {
                return true;
            }
        }

        return false;
    }

    public function isElement(string $locator): bool
    {
        $elements = $this->elementAccessor->getElements($locator);

        if (!empty($elements) && 1 === count($elements)) {
            return true;
        }

        return false;
    }

    public function isElementWithName(string $locator, string $name): bool
    {
        /** @var NodeElement[] $elements */
        $elements = $this->elementAccessor->getElements($locator);
        
        foreach ($elements as $element) {
            if (stristr($element->getText(), $name)) {
                return true;
            }
        }

        return false;
    }

    public function countElements(string $locator): int
    {
        return count($this->elementAccessor->getElements($locator));
    }
}
