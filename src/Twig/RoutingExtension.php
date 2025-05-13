<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Twig;

use BitBag\SyliusAclPlugin\Resolver\AdminPermissionResolverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\TwigFunction;

class RoutingExtension extends AbstractExtension
{
    public const ACCESS_DENIED = 'ACCESS_DENIED';

    public function __construct(
        protected UrlGeneratorInterface $generator,
        protected AdminPermissionResolverInterface $adminPermissionResolver,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('url', [$this, 'getUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('path', [$this, 'getPath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ];
    }

    public function getPath(
        string $name,
        array $parameters = [],
        bool $relative = false,
    ): string {
        if (!$this->adminPermissionResolver->resolvePermission($name)) {
            return self::ACCESS_DENIED;
        }

        return $this->generator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function getUrl(
        string $name,
        array $parameters = [],
        bool $schemeRelative = false,
    ): string {
        if (!$this->adminPermissionResolver->resolvePermission($name)) {
            return self::ACCESS_DENIED;
        }

        return $this->generator->generate($name, $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function isUrlGenerationSafe(Node $argsNode): array
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : (
            $argsNode->hasNode('1') ? $argsNode->getNode('1') : null
        );

        if (null === $paramsNode || $paramsNode instanceof ArrayExpression && 2 >= \count($paramsNode) &&
            (!$paramsNode->hasNode('1') || $paramsNode->getNode('1') instanceof ConstantExpression)
        ) {
            return ['html'];
        }

        return [];
    }
}
