<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Form\Transformer;

use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Symfony\Component\Form\DataTransformerInterface;
use Webmozart\Assert\Assert;

final class FormTransformer implements DataTransformerInterface
{
    public function __construct(
        private PrivilegeInterface $compositePrivilege,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function transform(mixed $value): mixed
    {
        Assert::nullOrIsArray($value);
        $tree = $this->compositePrivilege->getSupportedRouteMap([], true);
        $form = [];

        $value = $value ?? [];

        foreach ($tree as $resource => $actions) {
            foreach ($value as $privilege) {
                $this->buildFormSegment($privilege, $form, $actions, $resource);
            }
        }

        return $form;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function reverseTransform(mixed $value): mixed
    {
        Assert::isArray($value, Collection::class);

        $permissions = [];
        foreach ($value as $item) {
            foreach ($item as $i) {
                $permissions[] = $i;
            }
        }

        return $permissions;
    }

    private function buildFormSegment(
        string $privilege,
        array &$form,
        array $actions,
        string $resource,
    ): void {
        if (!in_array($privilege, array_keys($actions), true)) {
            return;
        }

        if (array_key_exists($resource, $form)) {
            $form[$resource][] = $privilege;

            return;
        }

        $form[$resource] = [$privilege];
    }
}
