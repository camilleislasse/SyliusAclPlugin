<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAclPlugin\Behat\Service\Accessor;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;

final class ElementAccessor implements ElementAccessorInterface
{
    /** @var Session */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getButtonElements(string $name): array
    {
        $elements = [];

        /** @var NodeElement $element */
        foreach ($this->session->getPage()->findAll('css', '.button') as $element) {
            if (stristr($element->getText(), $name)) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function getElements(string $locator): array
    {
        return $this->session->getPage()->findAll('css', $locator);
    }
}
